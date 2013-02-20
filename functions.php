<?  
/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
add_action( 'after_setup_theme', 'uw_setup' );

if ( ! function_exists( 'uw_setup' ) ): 

  function uw_setup() 
  {

	  add_theme_support( 'automatic-feed-links' );
	  add_theme_support( 'post-thumbnails' );

    add_image_size( 'Thimble', 50, 50, true );
    add_image_size( 'Sidebar', 250, 9999, false );
    add_image_size( 'Body Image', 300, 9999, false );
    add_image_size( 'Full Width', 620, 9999, false );

    add_image_size( 'thumbnail-large', 300, 300, true );

	  register_nav_menu( 'primary', __( 'Primary Menu', 'uw' ) );
	  register_nav_menu( 'footer', __( 'Footer Menu', 'uw' ) );

    define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyeleven_header_image_width', 1280 ) );
    define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyeleven_header_image_height', 215 ) );
    
	  add_theme_support( 'custom-header', array( 'random-default' => true ) );
	  //add_custom_image_header( 'uw_header_style', 'uw_admin_header_style', 'uw_admin_header_image' );
    
    register_default_headers( array(
      'blossoms' => array(
        'url' => '%s/../uw/img/header/cherries.jpg',
        'thumbnail_url' => '%s/../uw/img/header/cherries-thumbnail.jpg',
        'description' => __( 'Cherry Blossoms', 'uw' )
      )
    ));
  
  }

endif;


add_action( 'wp_enqueue_scripts', 'uw_enqueue_default_styles' );

if ( ! function_exists( 'uw_enqueue_default_styles' ) ): 
/**
 * This is where all the CSS files are registered
 *
 * bloginfo('template_directory')  gives you the url to the parent theme
 * bloginfo('stylesheet_directory')  gives you the url to the child theme
 */
  function uw_enqueue_default_styles() {
      global $current_blog;
      $is_child_theme = get_bloginfo('template_directory') != get_bloginfo('stylesheet_directory');
      wp_register_style( 'bootstrap',get_bloginfo('template_directory') . '/css/bootstrap.css', array(), '2.0.4' );
      wp_register_style( 'bootstrap-responsive', get_bloginfo('template_directory') . '/css/bootstrap-responsive.css', array('bootstrap'), '2.0.3' );
      wp_register_style( 'uw-master', get_bloginfo('template_url') . '/style.css', array('bootstrap-responsive'), '3.4.2.5' );
      if ( $is_child_theme)
        wp_register_style( 'uw-style', get_bloginfo('stylesheet_url'), array('bootstrap-responsive'), '3.4.1.2' );
      wp_register_style( 'google-font-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300' );
      wp_register_style( 'uw-gallery', get_bloginfo('template_directory') . '/css/gallery.css' );
      wp_enqueue_style( 'bootstrap' );
      wp_enqueue_style( 'bootstrap-responsive' );
      wp_enqueue_style( 'uw-master' );
      if ( $is_child_theme)
        wp_enqueue_style( 'uw-style' );
      wp_enqueue_style( 'google-font-open-sans' );

      wp_enqueue_style('uw-gallery');
      // Holiday video 2012
      if ( $current_blog->path === '/cms/discover/' 
            && get_query_var('pagename') === 'holiday')
      {
        wp_register_style( 'holiday-2012', get_bloginfo('template_directory') . '/css/holiday.css', array('bootstrap'), '2.0.3' );
        wp_enqueue_style( 'holiday-2012' );
      }
  }

endif;

add_action( 'wp_enqueue_scripts', 'uw_enqueue_default_scripts' );

if ( ! function_exists( 'uw_enqueue_default_scripts' ) ): 
/**
 * This is where all the JS files are registered
 *
 * bloginfo('template_directory')  gives you the url to the parent theme
 * bloginfo('stylesheet_directory')  gives you the url to the child theme
 */
  function uw_enqueue_default_scripts() {
    wp_deregister_script('jquery'); //we use googles CDN below
    wp_register_script( 'jquery','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', array(), '1.7.2' );
    wp_register_script( 'header', get_bloginfo('template_directory') . '/js/header.js', array('jquery'), '1.2.7' );
    wp_register_script( 'jquery.firenze', get_bloginfo('template_directory') . '/js/jquery.firenze.js', array('jquery'), '1.0.1' );
    wp_register_script( 'jquery.weather', get_bloginfo('template_directory') . '/js/jquery.weather.js', array('jquery'), '1.1' );
    wp_register_script( 'jquery.placeholder', get_bloginfo('template_directory') . '/js/jquery.placeholder.js', array('jquery'), '1.0' );
    wp_register_script( 'jquery.imageexpander', get_bloginfo('template_directory') . '/js/jquery.imageexpander.js', array('jquery'), '1.0.5' );
    wp_register_script( 'jquery.waypoints', get_bloginfo('template_directory') . '/js/jquery.waypoints.min.js', array('jquery'), '1.1.7' );
    wp_register_script( 'jquery.imagesloaded', get_bloginfo('template_directory') . '/js/jquery.imagesloaded.min.js', array('jquery'), '2.1.1' );
    wp_register_script( 'jquery.parallax', get_bloginfo('template_directory') . '/js/jquery.parallax.min.js', array('jquery'), '1.0' );
    wp_register_script( 'jquery.404', get_bloginfo('template_directory') . '/js/404.js', array('jquery'), '1.0' );
    wp_register_script( 'jquery.masonry', get_bloginfo('template_directory') . '/js/jquery.masonry.min.js', array('jquery') );

    wp_register_script( 'widget-youtube-playlist', get_bloginfo('template_directory') . '/js/widget-youtube-playlist.js', array('jquery','swfobject','jquery.imagesloaded') );
    wp_register_script( 'uw-gallery', get_bloginfo('template_directory') . '/js/gallery.js', array('jquery','jquery.imagesloaded'), '1.1' );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'header' );
    wp_enqueue_script( 'jquery.firenze' );
    wp_enqueue_script( 'jquery.placeholder' );
    wp_enqueue_script( 'jquery.imageexpander' );

    wp_enqueue_script('uw-gallery');


    if( is_404() ) {

      wp_enqueue_script( 'jquery.imagesloaded' );
      wp_enqueue_script( 'jquery.parallax' );
      wp_enqueue_script( 'jquery.404' );
       
    }
  }

endif;

add_action( 'admin_head', 'uw_admin_js_css' );
if ( ! function_exists( 'uw_admin_js_css' ) ):
  function uw_admin_js_css() 
  {
    wp_register_script( 'admin', get_bloginfo('template_directory') . '/admin/js/admin.js', array('jquery'),'1.1' );
    wp_enqueue_script('admin');

    wp_enqueue_style( 'admin', get_bloginfo('template_directory') . '/admin/css/admin.css' );
    wp_enqueue_style('admin');

    // no reason to register it
    wp_enqueue_style( 'google-font-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300' );
  }
endif;



if ( ! function_exists( 'uw_header_style' ) ): 
  function uw_header_style() {}
endif;

if ( ! function_exists( 'uw_admin_header_style' ) ): 
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function uw_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#headimg img {
		max-width: 1000px;
		height: auto;
		width: 100%;
	}
	</style>
<?php
}
endif;

if ( ! function_exists( 'uw_admin_header_image' ) ): 
  function uw_admin_header_image() {?>
  
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif;

if( ! function_exists('get_uw_breadcrumbs') ) :
  function get_uw_breadcrumbs()
  {
    global $post;

    $ancestors = array_reverse(get_post_ancestors($post->ID));
    $ancestors[] = $post->ID;
    $len = count($ancestors);
    if ( $len == 1 )
      return '';

    foreach ($ancestors as $index=>$ancestor) 
    {
      $class = $index+1 == count($ancestors) ? ' class="current" ' : '';
      $page  = get_post($ancestor);
      $url   = get_permalink($page->ID);
      $html .= "<li $class><a href=\"$url\" title=\"{$page->post_title}\">{$page->post_title}</a>";
    }
    return "<ul class=\"breadcrumbs-list\">$html</ul>";
  }
endif;

if( ! function_exists('uw_breadcrumbs') ) :
  function uw_breadcrumbs()
  {
    echo get_uw_breadcrumbs();
  }
endif;

if( ! function_exists('uw_breadcrumbs_on') ) :
  function uw_breadcrumbs_on()
  {
    return strlen(get_uw_breadcrumbs()) > 0;
  }
endif;



if ( ! function_exists( 'uw_dropdowns' ) ): 

  function uw_dropdowns() 
  {
    $nav = has_nav_menu('primary');
    if ( ( !$nav ) && ( is_multisite() ) )
    {
      switch_to_blog(1);
    }
    wp_nav_menu( array( 
      'theme_location'  => 'primary',
      'container_class' => 'nav-collapse',
      'menu_class'      => 'nav',
      'fallback_cb'     => '',
      'walker'          => new UW_Dropdowns_Walker_Menu(),
    ) );
    if ( ( !$nav ) && ( is_multisite() ) )
    {
      restore_current_blog();
    }
  }

endif;

if ( ! function_exists( 'uw_footer_menu') ) :
  function uw_footer_menu() 
  {
    $nav = has_nav_menu('footer');
    if ( ( !$nav ) && ( is_multisite() ) )
    {
      switch_to_blog(1);
    }

    $locations = get_nav_menu_locations();
    $menu = wp_get_nav_menu_object($locations['footer']);

    echo "<h2>{$menu->name}</h2>";
    wp_nav_menu( array( 
      'theme_location'  => 'footer',
      'menu_class'      => 'footer-navigation',
      'fallback_cb'     => '',
    ) );
    if ( ( !$nav ) && ( is_multisite() ) )
    {
      restore_current_blog();
    }
  }
endif;

if ( ! function_exists( 'uw_prev_next_links') ) :
  function uw_prev_next_links( $nav_id='prev-next' ) {
    global $wp_query;

    if ( $wp_query->max_num_pages > 1 ) :

        $big = 999999999; // need an unlikely integer
        $current = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $links = paginate_links( array(
          'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
          'format' => '?paged=%#%',
          'type' => 'array',
          'current' => max( 1, get_query_var('paged') ),
          'total' => $wp_query->max_num_pages
        ) ); 

      echo '<div class="pagination pagination-centered"><ul>';

      foreach ($links as $index=>$link) :

        $link = str_replace('span', 'a', $link);
        if ( strip_tags($link) == $current ) 
          echo "<li class=\"disabled\"><a href='javascript:void(0);'>$current</a></li>";
        else
          echo "<li>$link</li>";

      endforeach;

      echo '</ul></div>';




   endif;
  }
endif;


if ( ! function_exists( 'banner_class' ) ): 
  function banner_class() 
  {
    $option = get_option('patchband');

    if ( ! is_array($option) )
      return;

    $patch    = (object) $option['patch'];
    $band     = (object) $option['band'];
    $wordmark = (object) $option['wordmark'];

    $classes[] = 'header';

    if ( !$patch->header['visible'] ) 
      $classes[] = 'hide-patch';

    if ( $patch->header['color']== 'purple' )
      $classes[] = 'purple-patch';

    if ( $band->header['color']== 'tan' )
      $classes[] = 'tan-band';

    if ( $wordmark->header['color']== 'white' )
      $classes[] = 'wordmark-white';

    echo 'class="'. join(' ', $classes ) . '"';
  }
endif;

if ( ! function_exists( 'is_custom_wordmark' ) ): 
  function is_custom_wordmark()
  {
    $option = get_option('patchband'); 

    if ( ! is_array( $option) )
      return false;

    $wordmark = (array) $option['wordmark'];
    if ( isset($wordmark['custom'] )) 
      return true;

    return false;
  }
endif;

if ( ! function_exists( 'custom_wordmark' ) ): 
  function custom_wordmark() 
  {
    $option = get_option('patchband');

    if ( ! is_array( $option) )
      return;

    $wordmark = (array) $option['wordmark'];
    if ( isset($wordmark['custom'] )) {
      echo ' style="background:url('.$wordmark['custom']['url'].') no-repeat transparent; height:75px; width:445px;" ' ;
    }
  }
endif;


/**
 * Register's the default right widget sidebar
 */
if ( ! function_exists( 'uw_widgets_init' ) ): 

  function uw_widgets_init() 
  {
    $args = array(
      'name'          => 'Sidebar',
      'id'            => 'sidebar',
      'description'   => 'Widgets for the right column of the all '. get_bloginfo('name') . ' subpages',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget'  => '</div>'
    );

    register_sidebar($args);    

    $args = array(
      'name'          => 'Homepage Sidebar',
      'id'            => 'homepage-sidebar',
      'description'   => 'Widgets for the right column of the '. get_bloginfo('name') . ' homepage',
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget'  => '</div>'
    ); 
        
    register_sidebar($args);
    
  }

endif;

add_action( 'widgets_init', 'uw_widgets_init' );

add_filter('body_class','my_class_names');
if ( ! function_exists( 'uw_custom_body_classes' ) ):
  function my_class_names($classes) 
  {
    if (is_multisite())
        $classes[] = 'site-'. sanitize_html_class(str_replace('cms','',get_blog_details(get_current_blog_id())->path));
    $classes[] = is_home() && get_option('blogroll-banner') ? 'featured-image' : '';
    $classes[] = uw_breadcrumbs_on() ? 'breadcrumbs' : '';
    return $classes;
  }
endif;



/**
 * Filter the Welcome New User Email to replace 'USER_ROLE' in the text
 *  with the new user's role.
 *
 *  Test with this command: wpmu_welcome_user_notification($user_id,'password');
 */

if ( ! function_exists( 'uw_custom_welcome_email' ) ): 

  function uw_custom_welcome_email($welcome_email, $user_id) 
  {
    $user = get_userdata($user_id);
	  $welcome_email = str_replace( 'USER_ROLE', join($user->roles,','), $welcome_email );
    return $welcome_email;
  }

endif;
add_filter('update_welcome_user_email', 'uw_custom_welcome_email', 10, 2);


add_filter('the_content', 'force_https_the_content');
add_filter('the_permalink', 'force_https_the_content');
add_filter('post_thumbnail_html', 'force_https_the_content');
add_filter('option_siteurl', 'force_https_the_content');
add_filter('option_home', 'force_https_the_content');
add_filter('option_url', 'force_https_the_content');
add_filter('option_wpurl', 'force_https_the_content');
add_filter('option_stylesheet_url', 'force_https_the_content');
add_filter('option_template_url', 'force_https_the_content');

if ( ! function_exists( 'force_https_the_content' ) ):
  /**
   * For our setup, when a user is logged into WP he or she is
   * behind ssl. Imported, old content, however can still point to 
   * http, which causes some issued like images not loading (even though they 
   * are accessible through https). This function patches that issue specifically
   * for images.
   */
    function force_https_the_content($content) {
        if ( is_ssl() )
          $content = str_replace( 'src="http://', 'src="https://', $content );
        return $content;
    }

endif;

add_filter('wp_prepare_attachment_for_js', 'force_https_thumbnail_url');
add_filter('media_send_to_editor', 'force_https_thumbnail_url');
if ( ! function_exists( 'force_https_thumbnail_url') ):
  /**
   * The new media library in Wordpress 3.5 uses ajax for thumbnails, which
   * don't pass through any of the previous filters. Need to replace all url 
   * sizes.
   */
  function force_https_thumbnail_url($url) 
  {
    $ssl = is_ssl();
    $http = site_url(FALSE, 'http');
    $https = site_url(FALSE, 'https');
    if ( $ssl && is_array($url) && array_key_exists('sizes', $url) ) {
      foreach ($url['sizes'] as $index=>$value) {
        $url['sizes'][$index] = str_replace($http, $https, $url['sizes'][$index] );
      }
    }
    return ( $ssl ) ? str_replace($http, $https, $url) : $url;
  }

endif;


add_filter('sharing_permalink', 'uw_remove_cms_from_plugin_permalinks');
if ( ! function_exists( 'uw_remove_cms_from_plugin_permalinks') ):
  /**
   * Bug fix for the plugins that need site url. The plugin uses the default permalink 
   *  of the post which contains /cms/. This function and filter removes /cms/ from 
   *  the permalink.
   */
  function uw_remove_cms_from_plugin_permalinks($url) 
  {
      return defined('WP_LOCAL') ? $url : str_replace('/cms/','/', $url);  
  }
endif;

add_filter('wpcf7_form_class_attr', 'uw_add_wpcf7_bootstrap_class' );
if ( ! function_exists( 'uw_add_wpcf7_bootstrap_class') ):
  /**
   * Add the boostrap class to the contact form 7 form tag
   */
  function uw_add_wpcf7_bootstrap_class($class) 
  {
      return $class . ' form-horizontal';
  }
endif;

add_filter('excerpt_more', 'uw_excerpt_more');
if ( ! function_exists( 'uw_excerpt_more') ):
  /**
   * Added Excerpt filter
   */
   function uw_excerpt_more($more) 
   {
	  global $post;
   	return '... <a href="'. get_permalink($post->ID) . '">Read More</a>';
   }
endif;


add_filter('upload_mimes', 'uw_add_custom_upload_mimes');
/**
 * Wordpress doesn't know what a PSD is, so we have to tell it
 * */
if ( ! function_exists( 'uw_add_custom_upload_mimes' ) ):
function uw_add_custom_upload_mimes($existing_mimes){
    $existing_mimes['psd'] = 'image/photoshop'; //allow PSD files
    $existing_mimes['ai|eps'] = 'application/postscript';
    return $existing_mimes;
}
endif;

add_filter('wpcf7_ajax_loader', 'remove_cms_from_admin_url');
add_filter('wpcf7_form_action_url', 'remove_cms_from_admin_url');
add_filter('remove_cms', 'remove_cms_from_admin_url', 10, 2);
add_filter('wp_redirect', 'remove_cms_from_admin_url');
if ( ! function_exists( 'remove_cms_from_admin_url' ) ):
  function remove_cms_from_admin_url( $url, $forced=false) {
    global $blog_id;
    if ( ! defined('WP_LOCAL') && ! is_admin() && empty( $_SERVER['REMOTE_USER'] ) && $blog_id != 1 && !preg_match('/\b(wp-admin|wp-login|\/login)\b/i', $_SERVER['REQUEST_URI']) && !is_user_logged_in() 
          || $forced ) {
      $url = str_replace('.edu/cms/','.edu/', $url);
      $url = str_replace('https:','http:', $url);
      // relative urls
      if ( strpos($url,'http') === false ) 
        $url = str_replace( '/cms/','/', $url );
    }
    return $url;
  }
endif;

add_filter('sharing_show', 'uw_sharing_show');
if ( ! function_exists( 'uw_sharing_show' ) ):
  /**
   * If the blogroll is on the front page 
   * don't show any of the sharing links
   */
  function uw_sharing_show($show) 
  {
    return is_front_page() ? false : $show;
  }
endif;

add_filter('bloginfo_rss', 'uw_category_rss_link', 10, 2);
if ( ! function_exists( 'uw_category_rss_link') ):

  function uw_category_rss_link($arg, $show) {
    if ($show == 'url' && is_feed() && is_category() )
    {
      $id = get_query_var('cat');
      return get_category_link($id);
    };
    return $arg;
  }

endif;

/*
 * Will be used in WP 3.4.2 to fix our schedules posts bug
 *
add_filter('cron_request', 'uw_change_cron_address_to_cmswp');
if ( ! function_exists('uw_change_cron_address_to_cmswp') ):
  function uw_change_cron_address_to_cmswp($args) 
  {
    $args['url'] = str_replace('www.washington.edu',$_SERVER['SERVER_NAME'],$args['url']);
    return $args;
  }
endif;
 */



require( get_template_directory() . '/inc/documentation.php' );
require( get_template_directory() . '/inc/patch-band-options.php' );
require( get_template_directory() . '/inc/media-credit.php' );
require( get_template_directory() . '/inc/custom-media-urls.php' );
require( get_template_directory() . '/inc/custom-widgets.php' );
require( get_template_directory() . '/inc/custom-gallery.php' );
require( get_template_directory() . '/inc/custom-settings.php' );
require( get_template_directory() . '/inc/custom-image-sizes.php' );
require( get_template_directory() . '/inc/custom-shortcodes.php' );
require( get_template_directory() . '/inc/custom-embeds.php' );
require( get_template_directory() . '/inc/dropdown-walker.php' );
require( get_template_directory() . '/inc/helper-functions.php' );
//require( get_template_directory() . '/inc/json-api.php' );

if ( is_admin() )  {
  //if (!class_exists('coauthors_plus') )
    //require( get_template_directory() . '/admin/autocomplete-authors.php' );
  require( get_template_directory() . '/admin/custom-user-info-fields.php' );
}
?>
