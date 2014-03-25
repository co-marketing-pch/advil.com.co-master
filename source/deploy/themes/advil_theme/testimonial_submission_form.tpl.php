<?php $errors = form_get_errors(); ?>
<div id="testimonials-messages">
  <em class="required-fields-disclaimer"><?php print drupal_render($form['required_fields']); ?></em>
  <?php if (!empty($errors)) : ?>
    <div id="testimonials-form-errors">
      <ul>    
      <?php foreach ($errors as $error) : ?>
        <li><em>*<?php print $error; ?></em></li>
      <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>
<div class="holder-fields">
  <fieldset>
    <?php print drupal_render($form['name']); ?>
    <?php print drupal_render($form['product']); ?>
  </fieldset>
  <fieldset>
    <?php print drupal_render($form['email']); ?>
    <?php print drupal_render($form['email_confirm']); ?>
  </fieldset>
</div>
<div class="title-testimonial">
  <?php print drupal_render($form['title']); ?>
</div>

  <?php print drupal_render($form['body']); ?>
  <p class="disclaimer"><?php print drupal_render($form['disclaimer']); ?></p>

<div class="agreement clear_both">
  <span class="release-title"><?php print drupal_render($form['release_agreement_title']); ?></span>
  <?php print drupal_render($form['release_agreement_body']); ?>
  <div class="options">
    <?php print drupal_render($form['agreement']); ?>
  </div>
</div>
<?php
  // Always print out the entire $form. This renders the remaining pieces of the
  // form that haven't yet been rendered above.
  print drupal_render_children($form);
  