<?php 
$imgpath = file_create_url($node->field_background_image[$node->language][0]['uri']);
?>
<div class="marquee_content_wrapper" style="background: url(<?php print $imgpath; ?>)">

  <?php if (!empty($node->field_carousel_mp4_file)) :?>
    <div class="rotation_promotion_video" rel="<?php print $node->title; ?>">
      
    </div>
  <?php endif;
?>
  <?php if (!empty($node->body[$node->language][0]['value'])) : ?>
  <div class="rotation_promotion_text_wrapper">
    <div class="rotation_promotion_text" style="top:<?php print $node->field_vertical_align[$node->language][0]['value']; ?>px;
         left:<?php print $node->field_horizontal_align[$node->language][0]['value']; ?>px; right:<?php print (isset($node->field_horizontal_align_right[$node->language][0]['value']))?$node->field_horizontal_align_right[$node->language][0]['value']:''; ?>">
      <?php print $node->body[$node->language][0]['value']; ?>
    </div>
  </div>
  <?php endif; ?>
</div>