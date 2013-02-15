<?php
add_filter('post_gallery', 't_gallery', 10, 2);

function t_gallery($output, $attr)
{
	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';
  
  $attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
  
	if ( empty($attachments) )
		return '';

  $groups = array_chunk($attachments, 5);

  $html = '<div class="gallery"><a href="#" class="slideshow-left"></a><a href="#" class="slideshow-right"></a><div class="gallery-viewport-wrapper"><div class="gallery-viewport">';
  $menu = '';
  
  foreach ($groups as $i=>$images) 
  {
    $group = '';
    $large = '';

    foreach ($images as $index=>$image) 
    {

      $url = wp_get_attachment_image_src($image->ID);
      $url_large = wp_get_attachment_image_src($image->ID, 'thumbnail-large');
      $med_url = wp_get_attachment_image_src($image->ID, 'Full Width');
      $large_url = wp_get_attachment_image_src($image->ID, 'full');
      
      if ( $index == 4 ) {

        $large = '<div class="gallery-image" data-permalink-url="'.get_permalink($image->ID).'" data-url="'.$med_url[0].'" data-wp-url="'. $large_url[0] .'" style="background-image:url('.$url_large[0].')"><span><p class="image-title">'. $image->post_title .'</p><p>' . $image->post_excerpt .'</p></span></div>' .  "\n\n";


      } else {

        $group .= '<div class="gallery-image" data-permalink-url="'.get_permalink($image->ID).'" data-url="'. $med_url[0] .'" data-wp-url="'. $large_url[0] .'" style="background-image:url('.$url[0].');"><span><p class="image-title">'. $image->post_title . '</p><p>' . $image->post_excerpt . '</p><p class="text-shadow"></p></span></div>'. "\n\n";

      }

    };

    $menu .= '<li>'.$i.'</li>';

    $html .= ( $i % 2 ) ? 
      "<div class=\"large\">$large</div><div class=\"group\">$group</div>" :
      "<div class=\"group\">$group</div><div class=\"large\">$large</div>";
  }
  $html .= '</div></div><div class="gallery-table"><ul class="gallery-menu">'.$menu.'</ul></div></div><!-- .gallery -->';
  $overlay = '<div id="gallery-overlay-image" style="display:none">
                <img />
                <div class="description">
                  <div class="image-description"></div>
                  <div class="share">
                    <a class="gallery-facebook" data-href="http://www.facebook.com/sharer.php?u=" title="Facebook" target="_blank">Facebook</a>
                    <a class="gallery-twitter" data-href="http://twitter.com/?status=" title="Twitter" target="_blank">Twitter</a>
                    <span class="separator"></span>
                    <a class="gallery-original" href="#" title="Original Image" target="_blank">Original Image</a>
                  </div>
                </div>
                <a class="gallery-close">Close</a><a class="gallery-caption visible-phone" href="#">Show caption</a></div>';
  
  return $html . $overlay;

}
