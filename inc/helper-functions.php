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
