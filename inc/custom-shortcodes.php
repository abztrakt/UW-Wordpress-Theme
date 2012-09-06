<?php
/**
 * The Caption shortcode filter - original function in wp-includes/media.php
 */

add_filter('img_caption_shortcode', 'add_photo_credit_to_img_caption_shortcode',10,3);

if ( !function_exists('add_photo_credit_to_img_caption_shortcode')) :

  function add_photo_credit_to_img_caption_shortcode($val, $attr, $content = null)
  {
    extract(shortcode_atts(array(
      'id'	=> '',
      'align'	=> '',
      'width'	=> '',
      'caption' => ''
    ), $attr));
    
    if ( 1 > (int) $width || empty($caption) )
      return $content;

    if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

    preg_match('/([\d]+)/', $id, $match);

    if ( $match[0] ) $credit = get_post_meta($match[0], '_media_credit', true);

    if ( $credit ) $credit = '<p class="wp-media-credit">'. $credit . '</p>';

    return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . (10 + (int) $width) . 'px">'
    . do_shortcode( $content ) . $credit . '<p class="wp-caption-text">' . $caption . '</p></div>';
  }

endif;

/**
 * The RSS Shortcode
 */

if ( ! function_exists('uw_feed_shortcode') ):
  function uw_feed_shortcode( $atts ) 
  {
    extract( shortcode_atts( array(
        'url'    => null,
        'number' => 5,
        'title'  => null,
        'span'   => 4
      ), $atts ) );
    
    if ( $url == null )
      return '';

    $content = '';

    $feed = fetch_feed($url);


    if (!is_wp_error( $feed ) ) 
    { 
      $url = $feed->get_permalink();
      $feed_items = $feed->get_items(0, $number); 
      $feed_items = $feed->get_items(0, $number); 

      $title = ($title == null ) ? $feed->get_title() : $title;

      $content = "<div class=\"row pull-left\">";
      $content .= "<div class=\"span$span row\">";
      $content .= "<div class=\"feed-in-body\"><a href=\"$url\" title=\"$title\"><h3>$title</h3></a></div>";
      $content .= "<ul>";

      foreach ($feed_items as $index=>$item) 
      {
          $title = $item->get_title();
          $link  = $item->get_link();
          $attr  = esc_attr(strip_tags($title));
          $content .= "<li><a href=\"$link\" title=\"$attr\">$title</a></li>";
      }

      $span--;
      $content .= "<a href=\"$url\" title=\"$title\" class=\"offset$span\">More</a>";
      $content .= '</ul>';
      $content .= "</div></div>";
    }
    return $content;
  }
endif;
add_shortcode( 'rss', 'uw_feed_shortcode' );

/**
 * Archive Shortcode
 */

if ( ! function_exists('uw_archive_shortcode') ):
  function uw_archive_shortcode( $atts ) 
  {
    $params = shortcode_atts( array(
        'type'      => 'postbypost',
        'format'    => 'html',
        'limit'     => '',
        'showcount' => false,
        'before'    => '',
        'after'     => '',
        'order'     => 'desc'
      ), $atts );
    $params['echo'] = false;
    return '<div class="archive-shortcode">'. wp_get_archives($params) . '</div>';
  }
endif;
add_shortcode( 'archives', 'uw_archive_shortcode' );

/**
 * Blogroll Shorcode
 */
if ( ! function_exists('uw_blogroll_shortcode') ):
  function uw_blogroll_shortcode( $atts ) 
  {
    $params = shortcode_atts( array(
        'excerpt'      => 'true',
        'number'      =>  5
      ), $atts );

    $posts = get_posts("numberposts={$params['number']}");
    foreach ($posts as $post) {
      $link = get_permalink($post->ID);
      $excerpt = strlen($post->post_excerpt) > 0 ? $post->post_excerpt : wp_trim_words($post->post_content);
      $html .= "<li><a href=\"$link\">{$post->post_title}</a><p>{$excerpt}</p></li>";
    }
    return $html;
  }
endif;
add_shortcode( 'blogroll', 'uw_blogroll_shortcode' );
