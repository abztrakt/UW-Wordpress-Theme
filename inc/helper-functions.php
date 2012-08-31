<?php

/**
* Image helpers to display caption and description
*/

if (!function_exists('get_the_post_thumbnail_caption')) :

  function get_the_post_thumbnail_caption() 
  {
    return get_post(get_post_thumbnail_id())->post_excerpt; 
  }

endif;

if (!function_exists('the_post_thumbnail_caption')) :

  function the_post_thumbnail_caption() 
  {
    echo apply_filters('the_content', get_the_post_thumbnail_caption());
  }

endif;

if (!function_exists('get_the_blogroll_banner_url')) :

  function get_the_blogroll_banner_url() 
  {
    return wp_get_attachment_url(get_option('blogroll-banner'));
  }

endif;

if (!function_exists('the_blogroll_banner_url')) :

  function the_blogroll_banner_url() 
  {
    echo get_the_blogroll_banner_url(); 
  }

endif;

if (!function_exists('the_blogroll_banner_style')) :

  function the_blogroll_banner_style() 
  {
    if (is_home() && get_option('blogroll-banner')) 
      echo 'style="background-image:url(' . get_the_blogroll_banner_url() . ')"';
  }

endif;

if ( ! function_exists( 'is_local' ) ):

  function is_local() 
  {
    return defined('WP_LOCAL');
  }

endif;

