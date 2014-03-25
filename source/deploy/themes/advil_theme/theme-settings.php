<?php

/**
 * @file
 * Theme setting callbacks for the advil cr theme.
 */

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * @param $form
 *   The form.
 * @param $form_state
 *   The form state.
 */
function advil_form_system_theme_settings_alter(&$form, &$form_state) {
  $form['videos_page_fieldset'] = array(
    '#type' => 'fieldset',
    '#title' => t('Videos page'),
    '#collapsed' => TRUE,
    '#collapsible' => TRUE,  
  );
  
  $form['videos_page_fieldset']['videos_page_title'] = array(
    '#type' =>  'textfield',
    '#title' => t('Text for videos page title (H1)'),
    '#default_value' => theme_get_setting('videos_page_title'),  
  );
  
  $form['videos_page_fieldset']['videos_page_subtitle'] = array(
    '#type' =>  'textfield',
    '#title' => t('Text for videos page subtitle (H3)'),
    '#default_value' => theme_get_setting('videos_page_subtitle'),  
  );

  $form['article_detail_page_fieldset'] = array(
    '#type' => 'fieldset',
    '#title' => t('Article detail page'),
    '#collapsed' => TRUE,
    '#collapsible' => TRUE,  
  );
  
  $form['article_detail_page_fieldset']['article_detail_previous_button_text'] = array(
    '#type' =>  'textfield',
    '#title' => t('Article detail previous button text'),
    '#default_value' => theme_get_setting('article_detail_previous_button_text'),
  ); 
  
  $form['article_detail_page_fieldset']['article_detail_next_button_text'] = array(
    '#type' =>  'textfield',
    '#title' => t('Article detail next button text'),
    '#default_value' => theme_get_setting('article_detail_next_button_text'),
  );  
  
  $form['article_detail_page_fieldset']['article_detail_default_font_size'] = array(
    '#type' =>  'textfield',
    '#title' => t('Article default font size'),
    '#default_value' => theme_get_setting('article_detail_default_font_size'),  
  );
  
  $form['article_detail_page_fieldset']['article_detail_min_font_size_allowed'] = array(
    '#type' =>  'textfield',
    '#title' => t('Article detail min font size allowed'),
    '#default_value' => theme_get_setting('article_detail_min_font_size_allowed'),  
  );
  
  $form['article_detail_page_fieldset']['article_detail_max_font_size_allowed'] = array(
    '#type' =>  'textfield',
    '#title' => t('Article detail max font size allowed'),
    '#default_value' => theme_get_setting('article_detail_max_font_size_allowed'),  
  );
    
}
