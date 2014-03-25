<?php
/**
 * @file
 * Omniture common functions.
 */

function _omniture_set_default_js_variables() {
  $valid_item = menu_get_item();
  
  // replace patterns
  $patterns = array(
    '/^(.*)?\>(.*)?\<.*$/',
    '/&amp;/i',
    '/&#039;/i',
  );
  $replaces = array(
    '$2',
    'and',
    '',
  );

  $page_title = '404';
  if (!empty($valid_item) && $valid_item['access']) {
    $page_title = drupal_strtolower(preg_replace($patterns, $replaces, drupal_get_title()));
  }
  
  $active_breadcrumb = menu_get_active_breadcrumb();
  
  $breadcrumb = array();
  
  if (!empty($active_breadcrumb)) {
    foreach ($active_breadcrumb as $link) {
      $breadcrumb[] = drupal_strtolower(preg_replace($patterns, $replaces, $link));
    }
  }
  
  $object = menu_get_object();
  if (!empty($object)) {
    $object = array(
      'type' => $object->type,
      'title' => drupal_strtolower(preg_replace($patterns, $replaces, $object->title)),
    );
  }
  
  $channel = NULL;
  if (count($breadcrumb) > 1) {
    $channel = end($breadcrumb);
  }
  else {
    $channel = $page_title;
  }
  $breadcrumb[] = $page_title;
  
  $variables = array(
    's_account'     => variable_get('omniture_s_account', ''),
    'siteID'        => variable_get('omniture_siteID', ''),
    'page_title'    => $page_title,
    'is_home_page'  => drupal_is_front_page(),
    'breadcrumb'    => $breadcrumb,
    'channel'       => $channel,
    'object'        => $object,
  );
  
  drupal_add_js(array('omniture' => $variables), 'setting');
}
 
/**
 * Include current page js.
 * It assembles the header, custom page and footer javascript sources.
 */
function _omniture_include_current_page_js() {
  _omniture_set_default_js_variables();

  $output = '';
  
  // header
  $output .= file_get_contents(drupal_get_path('module', 'omniture') . '/scripts/s_code_header.js', 'r');
  
  // custom
  $output .= variable_get('omniture_default_js', '');
  $output .= _omniture_get_current_page_js();
  
  // footer
  $output .= file_get_contents(drupal_get_path('module', 'omniture') . '/scripts/s_code_footer.js', 'r');

  drupal_add_js($output, array('type' => 'inline', 'scope' => 'footer', 'weight' => 5));
}
 
/**
 * Return current page specific omniture javascript mixing all path match types.
 * Following this precedence:
 *   1. nothing
 *   2. content type definition
 *   3. menu router specification
 *   4. path exact match
 */
function _omniture_get_current_page_js() {
  $current_page = _omniture_get_path_info();
  if (empty($current_page)) {
    return '';
  }
  
  // 1
  $omniture_source = '';
  
  // 2
  $current_object = menu_get_object();
  if (!empty($current_object)) {
    $items = _omniture_get_items_by_content_type($current_object->type, 'content_type');
    if (!empty($items)) {
      $omniture_source .= $items[0]->source;
    }
  }
  
  // 3
  $items = _omniture_get_items_by_path($current_page['path_alias']);
  if (!empty($items)) {
    $omniture_source .= $items[0]->source;
  }
  
  // 4
  if ($current_page['router'] != $current_page['path_alias'] && $current_page['router'] != $current_page['normal_path']) {
    $items = _omniture_get_items_by_path($current_page['router']);
    if (!empty($items)) {
      $omniture_source .= $items[0]->source;
    }
  }
  
  return $omniture_source;
}

/**
 * Return all already defined content types in omniture table.
 */
function _omniture_get_defined_content_types() {
  $content_types = array();
  
  $query = db_select('omniture', 'o')
    ->fields('o')
    ->condition('o.type', 'content_type', '=');
  $result = $query->execute();
  
  foreach ($result as $row) {
    $content_types[$row->value] = $row->source;
  }
  
  return !empty($content_types) ? $content_types : FALSE;
}

/**
 * Insert new entry into omniture table.
 *
 * @param $fields
 *  Array containing all column fields.
 */
function _omniture_insert_new_entry($fields) {
  try {
    $entry = db_insert('omniture')
      ->fields($fields)
      ->execute();
      
    return $entry;
  }
  catch (Exception $e) {
    drupal_set_message(t('Omniture table insertion failed with the message: :msg', array(':msg' => $e->getMessage())), 'error');
  }
}

/**
 * Get path information.
 *
 * @param $path
 *   String of path to be checked, check current Url if NULL.
 *  
 * @return $path_info
 *   if menu is valid, return its:
 *    - normal path (e.g. node/3)
 *    - path alias  (e.g. products/product_name_1
 *    - menu router (e.g. products/%)
 *   empty array otherwise.
 */
function _omniture_get_path_info($path = NULL) {
  $path_info = array();
  
  // if path is empty, get current url
  if (empty($path)) {
    $path = menu_get_item();
    $path = $path['href'];
  }
  
  $normal_path = drupal_get_normal_path($path);
  $menu_router_path = _menu_find_router_path($normal_path);

  if (!empty($menu_router_path)) {
    $path_info = array(
      'normal_path' => $normal_path,
      'path_alias'  => drupal_get_path_alias($normal_path),
      'router'      => $menu_router_path,
    );
  }
  
  return $path_info;
}

/**
 * Load all items for the given $path. Check both normal path and possible aliases.
 *
 * @parm $path
 *   Path to be loaded.
 *
 * @return
 *   Entries in omniture table for the given $path. Should be only one item if used properly.
 */
function _omniture_get_items_by_path($path = NULL) {
  $items = NULL;

  $path_info = _omniture_get_path_info($path);
  
  $result = db_select('omniture', 'o')
    ->fields('o')
    ->condition(
      db_or()
      ->condition('o.value', $path_info['normal_path'], '=')
      ->condition('o.value', $path_info['path_alias'], '=')
    )
    ->condition('o.type', 'path', '=')
  ->execute();
  
  foreach ($result as $row) {
    $items[] = $row;
  }
  
  return $items;
}

/**
 * Load all items for the given $content_type.
 *
 * @parm $content_type
 *   Content type to be loaded.
 *
 * @return
 *   Entries in omniture table for the given $content_type. Should be only one item if used properly.
 */
function _omniture_get_items_by_content_type($content_type) {
  $items = array();
  
  if (empty($content_type)) {
    return $items;
  }
  
  $result = db_select('omniture', 'o')
    ->fields('o')
    ->condition('o.value', $content_type, '=')
    ->condition('o.type', 'content_type', '=')
  ->execute();
  
  foreach ($result as $row) {
    $items[] = $row;
  }
  
  return $items;
}