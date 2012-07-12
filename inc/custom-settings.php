<?php 
 
/**
 *
 * Words to Italicize settings field on the Reading Settings page.
 *
 */

 function uw_reading_settings_api_init() {
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
 
 add_action('admin_init', 'uw_reading_settings_api_init');
 
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
    $new_content = preg_replace($regex, "<em>$1</em>", $content); 

    // ampersand fix
    if ( in_array('&', $words) )
      $new_content = str_replace('&#038;', '<em>&#038;</em>', $content);

    echo $new_content;
 }

 add_filter('italics', 'uw_italicize_title');


 /**
  * Abbreviated Title/Acronym settings field on the General settings page.
  *   Used whenever the title is too long: Search Field, Explore the UW etc..
  *
  */
function uw_general_settings_api_init() {
    add_settings_field('Abbreviated',
      'Abbreviated Title',
      'abbreviation_form_html',
      'general',
      'default');
    register_setting('general','abbreviation');
 }
 
 function abbreviation_form_html() {
  echo '<input type="text" name="abbreviation" value="'.get_option('abbreviation').'"/>';
  echo '<p class="howto">An abbreviated title used in some circumstances when the full title is too long.</p>';
  echo "<script type='text/javascript'>
         jQuery(document).ready(function($){
          var input = $('input[name=abbreviation]').closest('tr');
          $('.form-table tr').eq(1).after(input);
         });
        </script>";
 }
 add_action('admin_init', 'uw_general_settings_api_init');


 function uw_abbreviate_title($title) {
   $abbr = get_option('abbreviation');
   if (! $abbr ) 
     return $title;
   return $abbr;
 }

 add_filter('abbreviation', 'uw_abbreviate_title');
