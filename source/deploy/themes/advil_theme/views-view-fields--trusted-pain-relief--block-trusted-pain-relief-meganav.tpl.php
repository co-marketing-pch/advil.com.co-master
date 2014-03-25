<div>  
  <?php if(isset($fields['field_mega_nav_img'])) : ?>
    <?php print $fields['field_mega_nav_img']->content; ?>
  <?php endif; ?>  
  <div class="content">
    <h3>
      <?php if(isset($fields['title'])) : ?>
        <?php print $fields['title']->content; ?>
      <?php endif; ?>
    </h3>
    <p>
      <?php if(isset($fields['field_tpr_subhead'])) : ?>
        <?php print $fields['field_tpr_subhead']->content; ?>
      <?php endif; ?>
    </p>
    <p>
      <?php if(isset($fields['link_to_content'])) : ?>
        <?php print $fields['link_to_content']->content; ?>
      <?php endif; ?>      
    </p>
  </div>
</div>