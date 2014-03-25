<div class="trusted-pain-relief-row">
  <div class="trusted-pain-relief-image">
    <?php if(isset($fields['field_int_thumb_img'])) : ?>
      <?php print $fields['field_int_thumb_img']->content; ?>
    <?php endif; ?>
  </div>
  <div class="trusted-pain-relief-content">
    <?php if(isset($fields['title'])) : ?>
      <?php print $fields['title']->content; ?>
    <?php endif; ?>
    <p>
      <?php if(isset($fields['field_tpr_subhead'])) : ?>
        <?php print $fields['field_tpr_subhead']->content; ?>
      <?php endif; ?>
    </p>
  </div>
</div>