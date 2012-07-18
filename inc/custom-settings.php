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
      $new_content = str_replace('&#038;', '<em>&#038;</em>', $new_content);

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

 /**
  * 
  * Custom Header color picker for the background color
  *
  * Since this page doesn't use the Settings API its a bit different.
  * I registered the customizations via the Settings API (for the future)
  *  and then print our the settings on the action 'custom_header_options'
  *
  */


 function uw_header_settings_api_init() {
 	add_settings_section('background_color',
		'Background Color',
		'_background_howto',
		'custom-header');
 	
 	add_settings_field('header_background_color',
		'Color',
		'header_background_color_html',
		'custom-header',
		'background_color');

 	register_setting('custom-header', 'header_background_color');

 }

 function _background_howto() { echo ''; }

 function header_background_color_html() {
   if ( isset($_POST['header_background_color'])) 
     update_option('header_background_color', $_POST['header_background_color']);

  $color = get_option('header_background_color');
  echo "<input type=\"text\" name=\"header_background_color\" id=\"bg-color\" value=\"$color\" /> ";
  echo '<a href="#" class="hide-if-no-js" id="bg-pickcolor">' . _( 'Select a Color' ) . '</a>';
  echo '<div id="bg-color-picker" style="z-index: 100; background:#eee; border:1px solid #ccc;width:250px;display:none;"></div>';
  echo '<div id="bg-color-preview" style="z-index: 100; background:#eee; border:1px solid #ccc;width:250px;display:none;"></div>';
  ?>
    <script type='text/javascript'>
      jQuery(document).ready(function($) {
        var $input   = $('#bg-color')
          , $headimg = $('#headimg')
        $('#bg-color-picker').farbtastic(function(color) {
          $input.val(color)
          $headimg.css('background-color', color)
        }) ;
          
        $('#bg-pickcolor').click(function() { $('#bg-color-picker').show(); return false; });
          
        $('h3').filter(function() {
          return $(this).text().indexOf('Text') !== -1;
        }).next('table').andSelf().remove(); //andSelf refers to <h3>Header Text</h3>

        $headimg.css('text-align', 'center').children().not('img').hide()
            .end().width('85%')

        $headimg.css('background-color', $input.val())

        $('body').click(function() { $('#bg-color-picker').hide() })
      })
    </script>
  <?php
 }
 
 function uw_print_header_settings() {
   do_settings_sections('custom-header', 'uw_adjust_custom_header_page');
 }
 
 add_action('admin_init', 'uw_header_settings_api_init');
 add_action('custom_header_options', 'uw_print_header_settings');

 function header_background_color() {
   echo get_header_background_color();
 }
 function get_header_background_color() {
   $color = get_option('header_background_color');

     if (!$color)
       return '';

   return "background-color:$color;";
 }
