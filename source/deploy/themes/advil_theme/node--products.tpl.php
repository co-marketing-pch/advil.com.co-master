<?php  
  $lang = LANGUAGE_NONE;
  
  if (isset($node->language)) {
    $lang = $node->language;
  }
  
  // receive 1, to list just the product
  $count_tabs = 1;
  
  // In case of more than one, $count_tabs get the value of the number of tabs
  if(isset($node->field_product_form_id[$lang])) {
    // get the number of tabs, to list each one
    $count_tabs = (count($node->field_product_form_id[$lang]));
  }
?>

<div id="product-detail">
  <?php
    for($i = 0; $i < $count_tabs; $i++) :

      $tab_id = "";
      if(isset($node->field_product_form_id[$lang][$i]['value'])) {
        $tab_id = $node->field_product_form_id[$lang][$i]['value'];
      }
  ?>
  <div class="product-detail-content" id="<?php print $tab_id; ?>" style="display:<?php print $i == 0 ? 'block' : 'none' ?>;">
    <h1><?php print $node->field_headline[$lang][$i]['value']; ?></h1>
    <h3>
      <?php 
        if(isset($node->field_subhead[$lang][0]['value'])) :
          print $node->field_subhead[$lang][0]['value']; 
        endif;
      ?>
    </h3>
    <div class="box-wrapper-products">
      <div class="product-images">
        <div class="box-image">
          <div class="box-image-content">
            <?php if (isset($node->field_image[$lang][$i])): ?>
              <?php $node->field_image[$lang][$i]['path'] = $node->field_image[$lang][$i]['uri']; ?>
              <?php print theme('image', $node->field_image[$lang][$i]); ?>
            <?php endif; ?>
          </div>
        </div>
        <?php if (isset($node->field_product_forms_image[$lang][$i])): ?>
          <div class="box-detail">          
            <ul class="product-detail-product-forms">
            <?php            
              foreach($node->field_product_forms_image[$lang] as $id => $form_image):
                $form_image['path'] = $form_image['uri'];
            ?>
                <li class="<?php print $node->field_product_form_id[$lang][$id]['value']; ?> <?php print ($i == 0 && $id == 0)? 'active' : 'not-active' ?>">
                  <a href="#<?php print $node->field_product_form_id[$lang][$id]['value']; ?>" title="<?php print $form_image['title']; ?>" name="<?php print $node->field_product_form_id[$lang][$id]['value']; ?>" class="avenir-medium">
                    <?php print theme('image', $form_image); ?>
                    <?php print $form_image['title']; ?>
                  </a>
                </li>
            <?php
              endforeach;            
            ?>
            </ul>
          </div>
        <?php endif; ?> 
      </div>
      <div class="product-texts">
        <div class="box-description">
          <?php if (isset($node->body[$lang][$i]['value'])): ?>
            <?php print $node->body[$lang][$i]['value']; ?>
          <?php endif; ?>
        </div>

      </div>
      
    </div>
	
    <?php if (isset($node->field_product_dosing_info_table[$lang][$i]['value'])): ?>
      <div class="product-dosing-info">
        <p class="thead"><?php print t('Dosing Information'); ?></p>                
        <?php print $node->field_product_dosing_info_table[$lang][$i]['value']; ?>        
      </div>    
    <?php endif; ?>

    <?php if (isset($node->field_product_labeling[$lang][$i]['value'])): ?>
      <div class="product-labeling-wrapper">
        <?php print t('Use as directed. Read complete warnings and information.'); ?>
        <?php print l(t('View Product Labeling') . ' »', '', array('attributes' => array('title' => t('View Product Labeling'), 'class' => 'product-labeling-link'))); ?>
        <div class="product-labeling-content">
          <?php print $node->field_product_labeling[$lang][$i]['value']; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (isset($node->field_references[$lang][$i]['value'])): ?>
      <div class="product-references-info">             
        <?php print $node->field_references[$lang][$i]['value']; ?>        
      </div>    
    <?php endif; ?>
	
  </div>
  <?php
    endfor;
  ?>
</div>
