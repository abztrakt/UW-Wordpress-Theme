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
		'Words to italicize',
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


 function uw_background_color_header_settings_api_init() {
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

  add_action('admin_init', 'uw_background_color_header_settings_api_init');
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


  /**
  * Custom header image for the blogroll page
  *
  *   This is the image that will show up as the banner of the blogroll page.
  *   Just as the background color above, the is printed out using the 'custom_header_options'
  *     action but still registered via the Settings API for future use.
  *
  */

  function uw_blogroll_banner_header_settings_api_init() {
  add_settings_section('blogroll_banner',
    'Blogroll Banner',
    '_blogroll_banner_howto',
    'custom-header');

  add_settings_field('blogroll_banner_image',
    'Image',
    'header_blogroll_banner_html',
    'custom-header',
    'blogroll_banner');

  register_setting('custom-header', 'blogroll_banner');
  }

  function _blogroll_banner_howto() { echo ''; }

  function header_blogroll_banner_html() { 
    if ( isset($_REQUEST['remove-blogroll-banner']) && 
      wp_verify_nonce($_REQUEST['_wpnonce-remove-blogroll-banner'], 'remove-blogroll-banner')) {
      update_option('blogroll-banner', false );
    } else if ( isset($_REQUEST['blogroll_banner_file']) && 
      wp_verify_nonce($_REQUEST['_wpnonce-blogroll-banner'], 'custom-header-blogroll-banner')) {
      update_option('blogroll-banner', $_REQUEST['blogroll_banner_file']);
    }
    $image_library_url  = get_upload_iframe_src( 'image', null, 'library' );
    $image_library_url  = remove_query_arg( 'TB_iframe', $image_library_url );
    $image_library_url  = add_query_arg( array( 'context' => 'custom-header-blogroll-banner', 'TB_iframe' => 1 ), $image_library_url );
    $blogroll_banner_id = get_option('blogroll-banner');
    $text = 'Choose an image from your library';

    if ($blogroll_banner_id) {
      $text = ' or choose a different image from your library';
      $blogroll_banner_thumb = wp_get_attachment_link($blogroll_banner_id, 'large') ;
    }
  ?>

    <p>
      <?php echo $blogroll_banner_thumb; ?>
      <?php if ( $blogroll_banner_id) : ?>
        <br/>
        <input type="hidden" name="_wpnonce-remove-blogroll-banner" value="<?php echo wp_create_nonce('remove-blogroll-banner'); ?>">
        <input type="submit" name="remove-blogroll-banner" id="remove-blogroll-banner" class="button" value="Remove Blogroll Banner">
      <?php endif; ?> 
      <label for="choose-from-library-link"><?php _e($text) ?></label>
      <a id="choose-from-library-link" class="button thickbox" href="<?php echo esc_url($image_library_url); ?>"><?php _e( 'Choose Image' ); ?></a>
    </p>

  <?php }
  
  add_action( 'admin_init', 'uw_blogroll_banner_header_settings_api_init');

  /**
   * Taken from wp-admin/custom-header.php to limit fields in the media uploader
   */
  function choose_blogroll_banner() {
		if ( isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'custom-header-blogroll-banner' ) {
      add_filter( 'attachment_fields_to_edit', 'blogroll_banner_attachment_fields_to_edit', 10, 2 );
      add_filter( 'media_upload_tabs', 'blogroll_banner_filter_upload_tabs' );
      add_filter( 'media_upload_mime_type_links', '__return_empty_array' );
    }
  }
  add_action( 'admin_menu', 'choose_blogroll_banner' );
  
	function blogroll_banner_attachment_fields_to_edit( $form_fields, $post ) {
		$form_fields = array();
		$href = esc_url(add_query_arg(array(
			'page' => 'custom-header',
			'step' => 2,
			'_wpnonce-blogroll-banner' => wp_create_nonce('custom-header-blogroll-banner'),
			'blogroll_banner_file' => $post->ID
		), admin_url('themes.php')));

		$form_fields['buttons'] = array( 'tr' => '<tr class="submit"><td></td><td><a data-location="' . $href . '" class="wp-set-header">' . __( 'Set as blogroll banner' ) . '</a></td></tr>' );
		$form_fields['context'] = array( 'input' => 'hidden', 'value' => 'custom-header-blogroll-banner' );

		return $form_fields;
	}
  
	function blogroll_banner_filter_upload_tabs() {
		return array( 'library' => __('Media Library') );
	}


/**
 * Add an option for which slider to put on the front-page 
 */

if ( class_exists('RoyalSliderAdmin') ): 

  function slider_royalslider_settings_api_init() {
    add_settings_section('royalslider',
    '',
    '_slider_howto',
    'reading');
    
    add_settings_field('homepage_royalslider',
    'Royalslider to show on homepage',
    'slider_royalslider_homepage_html',
    'reading',
    'royalslider');
    register_setting('reading','homepage_royalslider');

    add_settings_field('posts_per_frontpage',
      'Front page shows at most',
      'posts_per_frontpage_input_html',
      'reading',
    'default');
    register_setting('reading','posts_per_frontpage');

  }

  add_action('admin_init', 'slider_royalslider_settings_api_init');

  function _slider_howto() { echo ''; }

  function slider_royalslider_homepage_html() {
    $slider = get_option('homepage_royalslider');
    $output = "<input type=\"text\" name=\"homepage_royalslider\" value=\"$slider\" size=\"4\"/>";
    echo $output;
  }

  function posts_per_frontpage_input_html() {
    echo '<input name="posts_per_frontpage" type="number" step="1" min="1" value="'.get_option('posts_per_frontpage').'" class="small-text">';
  }

endif;
  

