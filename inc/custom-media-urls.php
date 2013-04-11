<?php
// This should solve the necessity to keep pdf url's the same after upload

function uw_edit_attachment_slug( $fields, $post )
{
  wp_enqueue_media();
  if ( strpos( get_post_mime_type($post->ID), 'application/') !== false )
  {
    $fields['replace_media'] = array(
        'label' => __('Replace Media'),
        'input' => 'html',
        'html' => '

        <style type="text/css">
        div.media-sidebar tr.compat-field-replace_media {
          display:none;
        }
        </style>

          <div class="wp-media-buttons uw-replace-media">
            <a href="#" class="button replace_media add_media" title="Replace Media">
              <span class="wp-media-buttons-icon"></span> 
              Replace
            </a>
            <em class="help"></em>
            '.wp_nonce_field('replace-media-' . $post->ID, 'replace-media-nonce', true, false) .'
            <input id="replace-media-input-'. $post->ID .'" type="hidden" name="replace_media_for_'.$post->ID.'" value=""/>
          </div>

            <script type="text/javascript">
            // Uploading files
            var file_frame;
             
              jQuery(".replace_media").live("click", function( event ){
             
                event.preventDefault();
             
                if ( file_frame ) {
                  file_frame.open();
                  return;
                }
             
                file_frame = wp.media.frames.file_frame = wp.media({
                  title: "Replace Media",
                  button: {
                    text: "Select" 
                  },
                  props: {
                    order:"ASC"
                  },
                  library : { 
                    type : "application" ,
                    post__not_in : ['.$post->ID.']
                  },
                  multiple: false  
                });
             
                file_frame.on( "select", function() {
                  attachment = file_frame.state().get("selection").first().toJSON();

                  jQuery("#replace-media-input-'. $post->ID .'").val(attachment.id)
                    jQuery("form#post").before("<div class=\"error below-h2\"><p>This media will be <b>permanently replaced</b> by <b>\""+ attachment.title + "\"</b> on " + (jQuery("#publish").val() || "Save" ) + "</p></div>");
                });
             
                file_frame.open();

              });

            </script>
        '
    );
  }
  
    return $fields;
}
add_filter( 'attachment_fields_to_edit', 'uw_edit_attachment_slug', 10, 2 );

function uw_save_attachment_slug( $attachment, $post_data )
{
  if ( strpos( get_post_mime_type($attachment['post_ID']), 'application/') !== false &&
        !empty($attachment['replace_media_for_'.$attachment['post_ID']]) ) {

    if ( !wp_verify_nonce($attachment['replace-media-nonce'], 'replace-media-'. $attachment['post_ID'] ) ) 
    {
       print 'You do not have access to replace the media file';
       exit;
    }

    $currentMediaID = $attachment['post_ID'];
    $newMediaID = $attachment['replace_media_for_'.$currentMediaID];

    $currentMedia = get_attached_file($currentMediaID);
    $newMedia = get_attached_file($newMediaID);

    if ( $newMedia ) {
      copy($newMedia, $currentMedia);
      wp_delete_attachment($newMediaID);
    }

  }

  if ( !empty( $post_data['post_name'] ) )
      $attachment['post_name'] = $post_data['post_name'];
  return $attachment;
}
add_filter( 'attachment_fields_to_save', 'uw_save_attachment_slug', 10, 2);
