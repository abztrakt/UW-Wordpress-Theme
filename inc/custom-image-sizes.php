<?php

function uw_get_additional_image_sizes($sizes) {

  $uw_sizes = array(
    'Body Image'=>'Half width',
    'Full Width'=>'Full width'
  );

  return array_slice( $sizes, 0, 2 ) + $uw_sizes + array_slice( $sizes, 2, null);
}

add_filter ( 'image_size_names_choose', 'uw_get_additional_image_sizes');
