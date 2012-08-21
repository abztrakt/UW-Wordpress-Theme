<?php
add_filter('img_caption_shortcode', 'add_media_credit_to_caption_shortcode_filter',10,3);

function add_media_credit_to_caption_shortcode_filter($val, $attr, $content = null)
{
	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));

	if ( 1 > (int) $width || empty($caption) )
		return $content;

  $credit = get_post_meta( $id , '_media_credit', true);
  //print_r(get_post($id));

	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . (10 + (int) $width) . 'px">'
	. do_shortcode( $content ) . '<p class="media-credit">'. $credit .'</p><p class="wp-caption-text">' . $caption . '</p></div>';

}

/**
 * Adding our custom fields to the $form_fields array
 *
 * @param array $form_fields
 * @param object $post
 * @return array
 */
function my_image_attachment_fields_to_edit($form_fields, $post) {
	// $form_fields is a special array of fields to include in the attachment form
	// $post is the attachment record in the database
	//     $post->post_type == 'attachment'
	// (attachments are treated as posts in WordPress)

	// add our custom field to the $form_fields array
	// input type="text" name/id="attachments[$attachment->ID][custom1]"

  // also only show this field when the context is meant to show it
  if ( ! in_array( $_REQUEST['context'],
                   array('custom-header-blogroll-banner', 'custom-header' )) )
  {
    $form_fields["media_credit"] = array(
      "label" => __("Image Credit"),
      "input" => "text", // this is default if "input" is omitted
      "value" => get_post_meta($post->ID, "_media_credit", true)
    );
    // if you will be adding error messages for your field,
    // then in order to not overwrite them, as they are pre-attached
    // to this array, you would need to set the field up like this:
    $form_fields["media_credit"]["label"] = __("Image Credit");
    $form_fields["media_credit"]["input"] = "text";
    $form_fields["media_credit"]["value"] = get_post_meta($post->ID, "_media_credit", true);
  }

	return $form_fields;
}
// attach our function to the correct hook
add_filter("attachment_fields_to_edit", "my_image_attachment_fields_to_edit", 100, 2);

/**
 * @param array $post
 * @param array $attachment
 * @return array
 */
function custom_image_attachment_fields_to_save($post, $attachment) {
	// $attachment part of the form $_POST ($_POST[attachments][postID])
	// $post attachments wp post array - will be saved after returned
	//     $post['post_type'] == 'attachment'
	if( isset($attachment['media_credit']) ){
		// update_post_meta(postID, meta_key, meta_value);
		update_post_meta($post['ID'], '_media_credit', $attachment['media_credit']);
	}
	return $post;
}
add_filter("attachment_fields_to_save", "custom_image_attachment_fields_to_save", null, 2);

?>
