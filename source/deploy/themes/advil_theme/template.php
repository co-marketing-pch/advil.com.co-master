<?php
function advil_preprocess_views_view(&$vars) {
  _advil_perform_view_preprocess(__FUNCTION__, $vars);  
}

/**
 * Gateway function responsible for calling appropriate preprocess function (if it exists),
 * according to the view/block being displayed.
 * 
 * @param string $preprocess_prefix
 * @param array $vars
 */
function _advil_perform_view_preprocess($preprocess_prefix, &$vars) {
  $view = $vars['view'];
  
  $function = implode('__', array($preprocess_prefix, $view->name, $view->current_display));
  
  if (function_exists($function)) {
    $function($vars);
  }
}

function advil_preprocess_views_view__relief_finder__relief_finder_block(&$vars) {
 $taxonomy_tree = array();
  
  $products = array();
  foreach($vars['view']->result as $term) {
    $taxonomy_tree[$term->tid] = array(
      'term_name' => $term->taxonomy_term_data_name,
      'tid'   => $term->tid,
    );

    $taxonomy_tree[$term->tid]['primary_recommendation'] = NULL;

    if (isset($term->field_field_primary_recommendation[0])) {
      $product_nid_primary = $term->field_field_primary_recommendation[0]['raw']['node']->nid;
      //Prevent from keep loading the products already loaded.
      
      if (!isset($products[$product_nid_primary])) {     
        // Get Product information
        $products[$product_nid_primary] = node_load($product_nid_primary);
      }

      $primary_recommendation_themed_image = NULL;
      if(!empty($products[$product_nid_primary]->field_product_relief_image[$term->field_field_primary_recommendation[0]['raw']['node']->language][0]['uri'])){
        $products[$product_nid_primary]->field_product_relief_image[$term->field_field_primary_recommendation[0]['raw']['node']->language][0]['path'] = $products[$product_nid_primary]->field_product_relief_image[$term->field_field_primary_recommendation[0]['raw']['node']->language][0]['uri'];
        $primary_recommendation_themed_image = theme('image', $products[$product_nid_primary]->field_product_relief_image[$term->field_field_primary_recommendation[0]['raw']['node']->language][0]);
      }

      $link_learn_more = 'node/'. $product_nid_primary;
      if (isset($products[$product_nid_primary]->field_external_product_url[$term->field_field_primary_recommendation[0]['raw']['node']->language])) {
        $link_learn_more = $products[$product_nid_primary]->field_external_product_url[$term->field_field_primary_recommendation[0]['raw']['node']->language][0]['value'];
      }
      
      //Primary recommendation
      $taxonomy_tree[$term->tid]['primary_recommendation'] = array(
        'title' => $products[$product_nid_primary]->title,
        'nid'   => $product_nid_primary,
        'link_learn_more' =>l(t('Learn more »'), $link_learn_more, 
                              array('attributes' => array('class' => array('learn-more'), 'title' => t('Learn more')))),
        'product_image'   => $primary_recommendation_themed_image,           
      );
    }

    $taxonomy_tree[$term->tid]['secondary_recommendation'] = NULL;

    if (isset($term->field_field_secondary_recommendation[0])) {    
      $product_nid_second = $term->field_field_secondary_recommendation[0]['raw']['node']->nid;
      //Prevent from keep loading the products already loaded.
      if (!isset($products[$product_nid_second])) {
        $products[$product_nid_second] = node_load($product_nid_second);
      }
      
      $secondary_recommendation_themed_image = NULL;
      if(!empty($products[$product_nid_second]->field_product_relief_image[$term->field_field_secondary_recommendation[0]['raw']['node']->language][0]['uri'])){
        $products[$product_nid_second]->field_product_relief_image[$term->field_field_secondary_recommendation[0]['raw']['node']->language][0]['path'] = $products[$product_nid_second]->field_product_relief_image[$term->field_field_secondary_recommendation[0]['raw']['node']->language][0]['uri'];
        $secondary_recommendation_themed_image = theme('image', $products[$product_nid_second]->field_product_relief_image[$term->field_field_secondary_recommendation[0]['raw']['node']->language][0]);
      }

      $link_learn_more = 'node/'. $product_nid_second;

      $target = '_self';
    
      if (isset($products[$product_nid_second]->field_external_product_url[$term->field_field_secondary_recommendation[0]['raw']['node']->language])) {
        $link_learn_more = $products[$product_nid_second]->field_external_product_url[$term->field_field_secondary_recommendation[0]['raw']['node']->language][0]['value'];
        $target = '_blank';
      }
      
      //Second recommendation
      $taxonomy_tree[$term->tid]['secondary_recommendation'] = array(
        'title' => $products[$product_nid_second]->title,  
        'nid'   => $product_nid_second,
        'link_learn_more' =>  l(t('Learn more »'), $link_learn_more, array(
          'attributes' => array(
            'class' => array(
              'learn-more-also-try'
            ),
            'title' => t('Learn more'), 'target' => $target
          )
        )),
        'product_image'   => $secondary_recommendation_themed_image,
      );
    }
  }
  
  if (module_exists('special_replace')) {
    module_load_include('inc', 'special_replace', 'special_replace.common');
    _special_replace_recursive($taxonomy_tree);
  }

  drupal_add_js(array('relief_finder' => $taxonomy_tree), 'setting');
} 

/**
* Implementation of hook_preprocess_views_view_fields
*
* @param array $vars
*/
function advil_preprocess_views_view_fields(&$vars) {
  _advil_perform_view_preprocess(__FUNCTION__, $vars);
}

/**
 * Implementation of hook_preprocess_node
 * @param array $vars
 */
function advil_preprocess_node(&$vars) {
  _advil_perform_node_preprocess(__FUNCTION__, $vars);
}


function advil_preprocess_html(&$vars){
 
 $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:image',
      'content' => file_create_url(variable_get('file_public_path', 'sites/default/files') . '/facebook_logo.png'),
    ),
  );
  drupal_add_html_head($element, 'advil_logo');
  
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:title',
      'content' => $vars['head_title']  ,
    ),
  );
  drupal_add_html_head($element, 'advil_title');
  
  "";
  
   $url = drupal_is_front_page() ? '<front>' : drupal_get_path_alias($_GET['q']);
  
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:url',
      'content' =>   url($url, array('absolute' => TRUE)),
    ),
  );
  drupal_add_html_head($element, 'advil_url');
}

/**
 * Gateway function responsible for calling appropriate preprocess function (if it exists),
 * according to the content type of the node being displayed.
 *
 * @param string $preprocess_prefix
 * @param array $vars
 */
function _advil_perform_node_preprocess($preprocess_prefix, &$vars) {
  $content_type = $vars['type'];

  $function = implode('__', array($preprocess_prefix, $content_type));

  if (function_exists($function)) {
    $function($vars);
  }
}

function advil_preprocess_node__webform(&$vars) {
  $vars['content']['webform']['#form']['#attributes']['class'][0] = $vars['content']['webform']['#form']['#attributes']['class'][0] .' webform-signup-email-updates';
}

function advil_pager__commercials_and_videos__videos_list(&$variables) {
  $items = _advil_get_pager_items_definition($variables);
  $items_enumeration = _advil_get_items_enumeration($variables);

  return $items_enumeration . theme('item_list', array(
    'items' => $items,
    'attributes' => array('class' => array('pager')),
  ));
}

/**
 * Return an array containing the definition of the pager items
 *
 * @global type $pager_page_array
 * @global type $pager_total
 *
 * @param type $variables
 *
 * @return array
 */
function _advil_get_pager_items_definition($variables) {
  $element = $variables['element'];
  $tags = $variables['tags'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  $pager_current = $pager_page_array[$element] + 1;
  $pager_max = $pager_total[$element];

  $li_previous = theme(
    'pager_previous',
    array(
      'text' => (isset($tags[1]) ? $tags[1] : t('Previous')),
      'element' => $element,
      'interval' => 1,
      'parameters' => $parameters,
    )
  );

  $previous_link_disabled_class = ($pager_current == 1) ? 'disabled' : '';
  $items[] = array(
    'class' => array('pager-previous', $previous_link_disabled_class),
    'data' => $li_previous ? $li_previous : t('Previous'),
  );

  $li_next = theme(
    'pager_next',
    array(
      'text' => (isset($tags[3]) ? $tags[3] : t('Next')),
      'element' => $element,
      'interval' => 1,
      'parameters' => $parameters,
    )
  );

  $next_link_disabled_class = ($pager_current == $pager_max) ? 'disabled' : '';
  $items[] = array(
    'class' => array('pager-next', $next_link_disabled_class),
    'data' => $li_next ? $li_next : t('Next'),
  );

  return $items;
}

/**
 * Return an string containing the HTML markup for the pager items couting
 *
 * @global type $pager_page_array
 * @global type $pager_limits
 * @global type $pager_total_items
 *
 * @param type $variables
 *
 * @return type
 */
function _advil_get_items_enumeration($variables) {
  $element = $variables['element'];

  global $pager_page_array, $pager_limits, $pager_total_items;
  $pager_items_per_page = $pager_limits[$element];
  $total_items = $pager_total_items[$element];
  $pager_current = $pager_page_array[$element] + 1;
  $first_item_index = ($pager_current * $pager_items_per_page) - $pager_items_per_page + 1;

  $last_item_index = $first_item_index + $pager_items_per_page - 1;
  if ($last_item_index > $total_items) {
    $last_item_index = $total_items;
  }

  $items_enumeration = t(
    '<p>Showing videos @from-@to of @total</p>',
    array(
      '@from'  => $first_item_index,
      '@to'    => $last_item_index,
      '@total' => $total_items
    )
  );

  return $items_enumeration;
}

function advil_preprocess_views_view__related_content__page(&$vars){
  
  $ogg_video_src = NULL;
  if(!empty($vars['view']->result[0]->_field_data['nid']['entity']->field_video_ogg_file)) {
    $ogg_video_src = file_create_url($vars['view']->result[0]->_field_data['nid']['entity']->field_video_ogg_file['und'][0]['uri']);
  }

  $poster_src = null; 
  if (isset($vars['field_video_background_img'][0]['uri'])) {
    $poster_src = file_create_url($vars['view']->result[0]->_field_data['nid']['entity']->field_video_background_img['und'][0]['uri']);
  }
  
  $settings = array(
    'related-video' => array(
      'poster_src' => $poster_src,
      'video_url' => array(
        'mp4' => file_create_url($vars['view']->result[0]->_field_data['nid']['entity']->field_video_mp4_file['und'][0]['uri']),
        'ogg' => $ogg_video_src,
      ),
    ),
  );

  drupal_add_js($settings, 'setting');
}

/**
 * Preprocess function for AdvilCR Video nodes
 * @param array $vars
 */
function advil_preprocess_node__video(&$vars) {
  $ogg_video_src = NULL;
  if(!empty($vars['field_video_ogg_file'][0]['uri'])) {
    $ogg_video_src = file_create_url($vars['field_video_ogg_file'][0]['uri']);
  }

  $poster_src = null; 
  if (isset($vars['field_video_background_img'][0]['uri'])) {
    $poster_src = file_create_url($vars['field_video_background_img'][0]['uri']);
  }
  
  $settings = array(
    'video' => array(
      'poster_src' => $poster_src,
      'video_url' => array(
        'mp4' => file_create_url($vars['field_video_mp4_file'][0]['uri']),
        'ogg' => $ogg_video_src,
      ),
    ),
  );

  drupal_add_js($settings, 'setting');
  
  // define facebook meta tags
  $mp4_video_url =  urlencode( file_create_url( $vars['field_video_mp4_file'][0]['uri'] ) );
  $absolute_path_to_theme = url('<front>', array('absolute' => TRUE)) . path_to_theme();
  $facebook_video_url = html_entity_decode($absolute_path_to_theme . "/swf/player.swf?file=" . $mp4_video_url . "&autostart=true&skin=" .  urlencode($absolute_path_to_theme . "/video_player_skin/advil.zip") . "&controlbar.position=over&controlbar.idlehide=true");
  
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:video',
      'content' => $facebook_video_url,
    ),
  );
  drupal_add_html_head($element, 'facebook_video_file');
  
  $secure_url = str_replace('http','https',$facebook_video_url);
  
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:video:secure_url',
      'content' => $secure_url,
    ),
  );
  drupal_add_html_head($element, 'facebook_video_file_secure');
  
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:video:height',
      'content' => '575',
    ),
  );
  drupal_add_html_head($element, 'facebook_video_height');
  
  
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:video:width',
      'content' => '323',
    ),
  );
  drupal_add_html_head($element, 'facebook_video_width');
  
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:video:type',
      'content' => 'application/x-shockwave-flash',
    ),
  );
  drupal_add_html_head($element, 'facebook_video_type');
  
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:type',
      'content' => 'movie',
    ),
  );
  drupal_add_html_head($element, 'facebook_type');
  
   
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:image',
      'content' => file_create_url($vars['field_video_thumb_image'][0]['uri']),
    ),
  );
  drupal_add_html_head($element, 'facebook_video_height');
  
   $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:title',
      'content' => html_entity_decode($vars['title'],ENT_QUOTES),
    ),
  );
  drupal_add_html_head($element, 'facebook_video_title');
  
  $element = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'property' => 'og:url',
      'content' => url('node/' . $vars['nid'], array('absolute' => TRUE)),
    ),
  );
  drupal_add_html_head($element, 'facebook_video_url');
}


function advil_preprocess_node__products(&$vars) {
  drupal_add_js(path_to_theme() . '/scripts/jquery.jscrollpane.min.js');
  drupal_add_js(path_to_theme() . '/scripts/product_labeling.js');
  
  $vars['node']->coupon_link = 'advil-coupons';
  if (isset($vars['node']->field_product_coupon[$vars['node']->language][0])) {
    $vars['node']->coupon_link = variable_get('coupons_email_updates_url', '') . '/' . $vars['node']->field_product_coupon[$vars['node']->language][0]['nid'];
  }
}

function advil_preprocess_views_view__testimonials__products_testimonials(&$vars) {
  $node = menu_get_object();
  $vars['header'] = str_replace('%5Bnid%5D', $node->nid, $vars['header']);
}

/**
 * Preprocess function for Rotation Promotion nodes
 * @param array $vars
 */
function advil_preprocess_node__rotation_promotion(&$vars) {
  static $video_index = 0;

  $ogg_video_src = NULL;
  if (!empty($vars['node']->field_carousel_ogg_file[$vars['node']->language][0]['uri'])) {
    $ogg_video_src = file_create_url($vars['node']->field_carousel_ogg_file[$vars['node']->language][0]['uri']);
  }

  if (!empty($vars['field_carousel_mp4_file'])) {
    $settings = array(
      'video' => array(
        $video_index => array(
          'poster_src' => file_create_url($vars['field_video_background_image'][0]['uri']),
          'video_url' => array(
            'mp4' => file_create_url($vars['node']->field_carousel_mp4_file[$vars['node']->language][0]['uri']),
            'ogg' => $ogg_video_src,
          )
        ),
      ),
    );

    drupal_add_js($settings, 'setting');

    $video_index++;
  }
}


/**
 * Preprocess function for Articles nodes
 * @param array $vars
 */
function advil_preprocess_node__article(&$vars) {
  $type_size_module = array(
    'type_size_default_font_size' => theme_get_setting('article_detail_default_font_size'),
    'type_size_min_font_size'     => theme_get_setting('article_detail_min_font_size_allowed'),
    'type_size_max_font_size'     => theme_get_setting('article_detail_max_font_size_allowed'),
  );
  drupal_add_js(array('article_type_size' => $type_size_module), 'setting');
}

function advil_preprocess_views_view__articles_by_category__page_article_by_category(&$vars) {
  drupal_set_title($vars['view']->result[0]->taxonomy_term_data_field_data_field_categories_name);
}


function advil_preprocess_views_view_fields__trusted_pain_relief__block_trusted_pain_relief_meganav(&$vars) {
  
  if(isset($vars['fields']['field_link']->content)) {    
    $vars['fields']['title']->content = l(
      htmlspecialchars_decode($vars['fields']['title']->content), 
      $vars['fields']['field_link']->content,
      array(
        'attributes' => array(          
          'title' => htmlspecialchars_decode($vars['fields']['title']->content)
        )
      )
    );    
    $vars['fields']['link_to_content']->content = l(
      t('Go'), 
      $vars['fields']['field_link']->content,
      array(
        'attributes' => array(
          'class' => array(
            'go-main-navigation'
          ),
          'title' => t('Go')
        )
      )
    );
  }
  else {    
    $vars['fields']['title']->content = l(
      htmlspecialchars_decode($vars['fields']['title']->content), 
      drupal_get_path_alias('node/' . $vars['fields']['nid']->content),
      array(
        'attributes' => array(           
          'title' => htmlspecialchars_decode($vars['fields']['title']->content)
        )
      )
    );
    $vars['fields']['link_to_content']->content = l(
      t('Learn More'), 
      drupal_get_path_alias('node/' . $vars['fields']['nid']->content),
      array(
        'attributes' => array(
          'class' => array(
            'learn-more'
          ),
          'title' => t('Learn More')
        )
      )
    );
  }
  
}

function advil_form_element($variables) {
  $element = &$variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  // Add a class for error elements to facilitate cross-browser styling.
  if (isset($element['#parents']) && form_get_error($element)) {
    $attributes['class'][] = 'form-element-error';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}

function advil_webform_element($variables) {
  // Ensure defaults.
  $variables['element'] += array(
    '#title_display' => 'before',
  );

  $element = $variables['element'];

  // All elements using this for display only are given the "display" type.
  if (isset($element['#format']) && $element['#format'] == 'html') {
    $type = 'display';
  }
  else {
    $type = (isset($element['#type']) && !in_array($element['#type'], array('markup', 'textfield'))) ? $element['#type'] : $element['#webform_component']['type'];
  }
  $parents = str_replace('_', '-', implode('--', array_slice($element['#parents'], 1)));
  
  $wrapper_classes = array(
   'form-item',
   'webform-component',
   'webform-component-' . $type,
  );
  if (isset($element['#title_display']) && $element['#title_display'] == 'inline') {
    $wrapper_classes[] = 'webform-container-inline';
  }
    // Add a class for error elements to facilitate cross-browser styling.
  if (isset($element['#parents']) && form_get_error($element)) {
    $wrapper_classes[] = 'form-element-error';
  }
  $output = '<div class="' . implode(' ', $wrapper_classes) . '" id="webform-component-' . $parents . '">' . "\n";
  $required = !empty($element['#required']) ? '<span class="form-required" title="' . t('This field is required.') . '">*</span>' : '';

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . _webform_filter_xss($element['#field_prefix']) . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . _webform_filter_xss($element['#field_suffix']) . '</span>' : '';

  switch ($element['#title_display']) {
    case 'inline':
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element['#description'])) {
    $output .= ' <div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}
