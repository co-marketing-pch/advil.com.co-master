<?php print drupal_render($form['name']); ?>

<label class="require-label"><span class="form-required">*</span> <?php print t('Required fields'); ?></label>
<fieldset>
    <?php print drupal_render($form['communication_tools_name']); ?>
    <?php print drupal_render($form['communication_tools_email_yours']); ?>
</fieldset>

<fieldset>
    <?php print drupal_render($form['communication_tools_friends_name']); ?>
    <?php print drupal_render($form['communication_tools_email_friend']); ?>
</fieldset>

<?php print drupal_render($form['form_build_id']) ?>
<?php print drupal_render($form['form_id']) ?>

<?php 
  if(isset($form['captcha'])){   
    //In case there is a form without CAPTCHA, this won't generate any errors/warnings
    $description = variable_get('communication_tools_captcha_description_text', isset($form['captcha']['#description'])?$form['captcha']['#description']:t('Enter the characters in the image:'));
    //Description will try to get the text that was chosen on the Communication Tools settings admin interface,
    //If it doesn't find anything, then it'll try to get the CAPTCHA description, and if even  then it's not found, use a generic text
    $form['captcha']['captcha_widgets']['captcha_response']['#description'] = "";
    $form['captcha']['captcha_widgets']['captcha_response']['#title'] = $description;
    print drupal_render($form['captcha']);
  }
?>

<div class="container-buttons clearfix">
    <?php print drupal_render($form['communication_tools_cancel_button']); ?>
    <div id="communication-tools-confirm-button">
        <?php print drupal_render($form['communication_tools_submit_button']); ?>
    </div>
</div>

