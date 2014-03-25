<h1><?php print $node->title; ?></h1>
<h2 class="avenir-medium"><?php print $node->field_tpr_subhead[$node->language][0]['value']; ?></h2>
<?php
  if( isset( $node->field_int_page_img[$node->language] ) ){
?>
<div id="trp_header_image">
  <?php
     $node->field_int_page_img[$node->language][0]['path'] = $node->field_int_page_img[$node->language][0]['uri'];
     print theme('image', $node->field_int_page_img[$node->language][0]);
  ?>
</div>
<?php
  }
  if( isset( $node->body[$node->language] ) ){
?>
<div id="tpr_body_content">
  <?php print $node->body[$node->language][0]['value']; ?>
</div>
<?php
  }
?>