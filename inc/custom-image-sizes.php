<?php
// This code was adopted from the plugin below
/*
Plugin name: KC Enhanced Attachment Form Pt. 1
Plugin URI: http://kucrut.org/2011/02/insert-image-with-custom-size-into-post/
Description: Provides the ability to insert images with defined custom sizes into post.
Version: 0.2.5
Author: Dzikri Aziz
Author URI: http://kucrut.org/
License: GPL v2
*/

function uw_get_additional_image_sizes() {
	$sizes = array();
	global $_wp_additional_image_sizes;
	if ( isset($_wp_additional_image_sizes) && count($_wp_additional_image_sizes) ) {
		$sizes = apply_filters( 'intermediate_image_sizes', $_wp_additional_image_sizes );
		//$sizes = apply_filters( 'uw_get_additional_image_sizes', $_wp_additional_image_sizes );
	}

	return $sizes;
}

function uw_additional_image_size_input_fields( $fields, $post ) {
	if ( !isset($fields['image-size']['html']) || substr($post->post_mime_type, 0, 5) != 'image' )
		return $fields;

	$sizes = uw_get_additional_image_sizes();
	if ( !count($sizes) )
		return $fields;

	$items = array();
	foreach ( array_keys($sizes) as $size ) {
		$downsize = image_downsize( $post->ID, $size );
		$enabled = $downsize[3];
		$css_id = "image-size-{$size}-{$post->ID}";
		$label = apply_filters( 'uw_image_size_name', $size );

    // remove Tile sizes from selection
    if ( is_numeric(strpos($size, 'Tile') )) continue; 

    // remove Thimble sizes from selection
    if ( is_numeric(strpos($size, 'Thimble') )) continue; 

		$html  = "<div class='image-size-item'>\n";
		$html .= "\t<input type='radio' " . disabled( $enabled, false, false ) . "name='attachments[{$post->ID}][image-size]' id='{$css_id}' value='{$size}' />\n";
		$html .= "\t<label for='{$css_id}'>{$label}</label>\n";
		if ( $enabled )
			$html .= "\t<label for='{$css_id}' class='help'>" . sprintf( "(%d&nbsp;&times;&nbsp;%d)", $downsize[1], $downsize[2] ). "</label>\n";
		$html .= "</div>";

		$items[] = $html;
	}

	$items = join( "\n", $items );
	$fields['image-size']['html'] .= "<hr/>";
	$fields['image-size']['html'] = "{$fields['image-size']['html']}\n{$items}";

	return $fields;
}

add_filter( 'attachment_fields_to_edit', 'uw_additional_image_size_input_fields', 11, 2 );
