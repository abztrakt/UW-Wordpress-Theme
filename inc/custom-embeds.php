<?php

add_action('init', 'uw_init_settings');

if ( ! function_exists( 'uw_init_settings') ):
  function uw_init_settings() 
  {
    wp_oembed_add_provider('http://uw.edu/maps/*', 'http://www.washington.edu/maps/api/oembed/place/');
  }
endif;
