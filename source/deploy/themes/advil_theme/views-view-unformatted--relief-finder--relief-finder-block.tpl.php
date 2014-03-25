<?php
/**
 * @file views_view_unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<div id="relief_finder_wrapper">
  <h2><?php print t('Advil&reg; Pain Relief Finder.'); ?> </h2>
  <div id="relief_finder_container">
    <div id="relief_finder_content">
      <h3><?php print t('Find the Advil&reg; Pain Relief product that’s right for you.') ?></h3>
      <div id="relief_finder_symptons">
        <p><?php print t('What is your symptom?'); ?></p>
        <ul id="symptons_itens_list">
        </ul>
        <div class="submit">
          <input class="submit_disabled" id="submit_relief_finder" name="paint_relief_finder_submit" title="<?php print t('Submit')?>" type="submit" value="Submit"/>
        </div>
      </div>
    </div>
  </div>
  <div id="relief_finder_recomendation_container">
      <div id="relief_finder_recomendation_content">
          <div class="recommend">
              <p>Te sugerimos:</p>
              <div class="recommend_content">
              </div>
          </div>
          <div class="also_try">
              <p>También puede probar:</p>
              <div class="also_try_content">
              </div>
          </div>
          <a href="#find_another" id="find_another_product_link" title="<?php print t('Find another product')?>">Buscar otro producto »</a>
      </div>
  </div>
  <div id="relief_finder_bottom"></div>
</div>
