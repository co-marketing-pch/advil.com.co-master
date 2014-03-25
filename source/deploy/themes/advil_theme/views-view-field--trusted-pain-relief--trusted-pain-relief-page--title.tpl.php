<?php
 /**
  * This template is used to print a single field in a view. It is not
  * actually used in default Views, as this is registered as a theme
  * function which has better performance. For single overrides, the
  * template is perfectly okay.
  *
  * Variables available:
  * - $view: The view object
  * - $field: The field handler object that can process the input
  * - $row: The raw SQL result that can be used
  * - $output: The processed output that will normally be used.
  *
  * When fetching output from the $row, this construct should be used:
  * $data = $row->{$field->field_alias}
  *
  * The above will guarantee that you'll always get the correct data,
  * regardless of any changes in the aliasing that might happen if
  * the view is modified.
  */
  if(isset($row->field_field_link[0])){
     $link =  $row->field_field_link[0]['raw']['value'];
  }
  else {
     $link =  'node/' .$row->nid;
  }
  
  if(isset($row->field_field_open_new_tab[0])) {
    $target = '_blank';
  }
  else {
    $target = '_self';
  }
  
  $output = l(
        htmlspecialchars_decode($row->node_title), 
        $link,
        array(
          'attributes' => array(
            'class' => array(
              'avenir-std-light',
              'tpr-title'
            ), 
            'title' => htmlspecialchars_decode($row->node_title),
            'target' => $target
          )
        )
      );
?>
<?php print $output; ?>