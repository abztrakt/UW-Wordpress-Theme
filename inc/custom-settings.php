<?php 
 
 function eg_settings_api_init() {
 	add_settings_section('italics_section',
		'',
		'_italics_howto',
		'reading');
 	
 	add_settings_field('italicized_words',
		'Words to italize',
		'italicized_words_form_html',
		'reading',
		'italics_section');
 	register_setting('reading','italicized_words');

 }
 
 add_action('admin_init', 'eg_settings_api_init');
 
 function _italics_howto() { echo ''; }
 
 function italicized_words_form_html() {
  $output = '<textarea name="italicized_words" rows="10" cols="50" class="large-text code">'.get_option('italicized_words').'</textarea>';
  echo $output;
 }


 function uw_italicize_title($content) {
    $words = explode(' ', get_option('italicized_words'));
    if ( 1 == sizeof($words) )  { 
      echo $content;
      return;
    }
    $words = array_filter(array_map('trim', $words));
    $regex = '/\b(' . implode("|", $words) . ')\b/i';
    echo preg_replace($regex, "<em>$1</em>", $content); 
 }

 add_filter('italics', 'uw_italicize_title');
