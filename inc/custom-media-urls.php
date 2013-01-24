<?php
// This should solve the necessity to keep pdf url's the same after upload

function uw_edit_attachment_slug( $fields, $post )
{
  if ( strpos( get_post_mime_type($post->ID), 'application/') !== false )
  {
    $fields['post_name'] = array(
        'label' => __('Slug'),
        'value' => $post->post_name
    );
  }
    return $fields;
}
add_filter( 'attachment_fields_to_edit', 'uw_edit_attachment_slug', 10, 2 );

function uw_save_attachment_slug( $attachment, $POST_data )
{
    if ( !empty( $POST_data['post_name'] ) )
        $attachment['post_name'] = $POST_data['post_name'];
    return $attachment;
}
add_filter( 'attachment_fields_to_save', 'uw_save_attachment_slug', 10, 2);
