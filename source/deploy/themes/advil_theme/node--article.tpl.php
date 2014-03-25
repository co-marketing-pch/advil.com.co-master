<h1><?php print $node->title; ?></h1>
<div class="slider-wrapper">
  <div class="slider-resizer-toolbar">
    <span class="min-resizing-font">Aa</span>
    <div class="article-type-size-block">
      <a href="#fade-font-size" class="fade-font-size" title="<?php print t('Fade font size'); ?>"><?php print t('Fade font'); ?></a>
      <a href="#expand-font-size" class="expand-font-size" title="<?php print t('Expand font size'); ?>"><?php print t('Expand font'); ?></a>
    </div>
    <span class="max-resizing-font">Aa</span>
  </div>
</div>
<?php if (isset($node->field_detail_image[$node->language])) { 
    $node->field_detail_image[$node->language][0]['path'] = $node->field_detail_image[$node->language][0]['uri'];
    print theme('image', $node->field_detail_image[$node->language][0]);
} ?>
<div class="article-content">
  <?php print $node->body[$node->language][0]['value']; ?>
</div>

  <div class="next-previous">
  
    <!-- PREVIOUS ARTICLE -->
    <?php 
    $previous_article = theme_get_setting('article_detail_previous_button_text');
    if (isset($node->field_previous_article[$node->language])) { 
      print l(t($previous_article), 
        'node/'. $node->field_previous_article[$node->language][0]['nid'], 
        array('attributes' => array('title' => $previous_article, 'class' => 'previous-article control-button'))
       );
    }
    else { ?>
      <span class="disabled previous-article control-button"><?php print t($previous_article); ?></span>
    <?php } ?>
      
    <!-- NEXT ARTICLE -->
    <?php 
    $next_article = theme_get_setting('article_detail_next_button_text');
    if (isset($node->field_next_article[$node->language])) { 
      print l(t($next_article), 
        'node/'. $node->field_next_article[$node->language][0]['nid'], 
        array('attributes' => array('title' => $next_article, 'class' => 'next-article control-button'))
       );
    } 
    else { ?>
      <span class="disabled next-article control-button"><?php print t($next_article); ?></span>
    <?php } ?>  
  </div>

</div>
