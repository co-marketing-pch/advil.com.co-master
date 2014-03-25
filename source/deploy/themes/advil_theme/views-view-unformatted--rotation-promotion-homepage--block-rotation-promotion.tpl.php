<?php
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
  <div id="rotation_promotion_carousel">
    <ul>
      <?php foreach ($rows as $id => $row): ?>
      <li>
        <div class="<?php print $classes_array[$id]; ?>">
          <?php print $row; ?>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
    <div id="rotation_promotion_carousel_controls">
      <a href="#" id="rotation_promotion_carousel_previous" title="<?php print t('Previous') ?>"><?php print t('Previous') ?></a>
      <a href="#" id="rotation_promotion_carousel_next" title="<?php print t('Next') ?>"><?php print t('Next') ?></a>
    </div>
  </div>