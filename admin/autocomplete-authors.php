<?php

wp_register_script( 'jquery.combobox.js', get_template_directory_uri() . '/admin/js/jquery.combobox.js', array('jquery-ui-core'), '1.0', true); 

wp_enqueue_style( 'jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css'); 
wp_enqueue_script('jquery-ui-autocomplete');
wp_enqueue_script( 'jquery.combobox.js');


add_filter('wp_dropdown_users', 'autocomplete_wp_dropdown_user_list');

if ( !function_exists('autocomplete_wp_dropdown_user_list')) :

  function autocomplete_wp_dropdown_user_list($html) {
    // works with jquery.combobox.js, this css comes from the combobox example on the jquery ui site
      return $html .= '
        <style>
          .ui-combobox {
            position: relative;
            display: inline-block;
          }
          .ui-combobox-toggle {
            position: absolute;
            top: 0;
            bottom: 0;
            margin-left: -1px;
            padding: 0;
            /* adjust styles for IE 6/7 */
            *height: 1.7em;
            *top: 0.1em;
          }
          .ui-combobox-input {
            margin: 0;
            padding: 0.3em;
          }
    </style> ';
    
  }

endif;

?>
