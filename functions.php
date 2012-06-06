<?php

add_action( 'after_setup_theme', 'uw_setup' );

if ( ! function_exists( 'uw_setup' ) ): 

  function uw_setup() 
  {

	  add_theme_support( 'automatic-feed-links' );
	  add_theme_support( 'post-thumbnails' );

    add_image_size( 'Thimble', 50, 50, true );
    add_image_size( 'Sidebar', 250, 9999, false );
    add_image_size( 'Body Image', 300, 9999, false );
    add_image_size( 'Full Width', 600, 9999, false );

	  register_nav_menu( 'primary', __( 'Primary Menu', 'uw' ) );

    define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyeleven_header_image_width', 1280 ) );
    define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyeleven_header_image_height', 215 ) );
    
	  add_theme_support( 'custom-header', array( 'random-default' => true ) );
	  add_custom_image_header( 'uw_header_style', 'uw_admin_header_style', 'uw_admin_header_image' );
    

    register_default_headers( array(
      'wheel' => array(
        'url' => '%s/../twentyeleven/images/headers/wheel.jpg',
        'thumbnail_url' => '%s/../twentyeleven/images/headers/wheel-thumbnail.jpg',
        /* translators: header image description */
        'description' => __( 'Wheel', 'twentyeleven' )
      ),
      'shore' => array(
        'url' => '%s/../twentyeleven/images/headers/shore.jpg',
        'thumbnail_url' => '%s/../twentyeleven/images/headers/shore-thumbnail.jpg',
        /* translators: header image description */
        'description' => __( 'Shore', 'twentyeleven' )
      ),
      'trolley' => array(
        'url' => '%s/../twentyeleven/images/headers/trolley.jpg',
        'thumbnail_url' => '%s/../twentyeleven/images/headers/trolley-thumbnail.jpg',
        /* translators: header image description */
        'description' => __( 'Trolley', 'twentyeleven' )
      ),
      'pine-cone' => array(
        'url' => '%s/../twentyeleven/images/headers/pine-cone.jpg',
        'thumbnail_url' => '%s/../twentyeleven/images/headers/pine-cone-thumbnail.jpg',
        /* translators: header image description */
        'description' => __( 'Pine Cone', 'twentyeleven' )
      ),
      'chessboard' => array(
        'url' => '%s/../twentyeleven/images/headers/chessboard.jpg',
        'thumbnail_url' => '%s/../twentyeleven/images/headers/chessboard-thumbnail.jpg',
        /* translators: header image description */
        'description' => __( 'Chessboard', 'twentyeleven' )
      ),
      'lanterns' => array(
        'url' => '%s/../twentyeleven/images/headers/lanterns.jpg',
        'thumbnail_url' => '%s/../twentyeleven/images/headers/lanterns-thumbnail.jpg',
        /* translators: header image description */
        'description' => __( 'Lanterns', 'twentyeleven' )
      ),
      'willow' => array(
        'url' => '%s/../twentyeleven/images/headers/willow.jpg',
        'thumbnail_url' => '%s/../twentyeleven/images/headers/willow-thumbnail.jpg',
        /* translators: header image description */
        'description' => __( 'Willow', 'twentyeleven' )
      ),
      'hanoi' => array(
        'url' => '%s/../twentyeleven/images/headers/hanoi.jpg',
        'thumbnail_url' => '%s/../twentyeleven/images/headers/hanoi-thumbnail.jpg',
        /* translators: header image description */
        'description' => __( 'Hanoi Plant', 'twentyeleven' )
      )
    ));
  
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

if ( ! function_exists( 'uw_dropdowns' ) ): 

  function uw_dropdowns() 
  {
    $nav = has_nav_menu('primary');
    if ( !$nav ) 
    {
      switch_to_blog(1);
    }
    wp_nav_menu( array( 
      'theme_location'  => 'primary',
      'container_class' => 'nav-collapse',
      'menu_class'      => 'nav',
      'fallback_cb'     => '',
      'walker'          => new Walker_Navbar_Menu(),
    ) );
    if ( !$nav ) 
    {
      restore_current_blog();
    }
  }

endif;
//add_filter( 'wp_nav_menu_args', 'uw_wp_nav_menu_args' );

if ( ! function_exists( 'uw_wp_nav_menu_args' ) ): 

  function uw_wp_nav_menu_args( $args ) 
  {
    $args['walker'] = new Walker_Navbar_Menu();
    return $args;
  }

endif;

if ( ! function_exists( 'banner_class' ) ): 
  function banner_class() 
  {
    $option = get_option('patchband');
    $patch = (object) $option['patch'];
    $band  = (object) $option['band'];

    $classes[] = 'header';

    if ( !$patch->header['visible'] ) 
      $classes[] = 'hide-patch';

    if ( $patch->header['color']== 'purple' )
      $classes[] = 'purple-patch';

    if ( $band->header['color']== 'tan' )
      $classes[] = 'tan-band';

    echo 'class="'. join(' ', $classes ) . '"';
  }
endif;

/**
 * Register's the default right widget sidebar
 */
if ( ! function_exists( 'uw_widgets_init' ) ): 

  function uw_widgets_init() 
  {
    register_sidebars();    
  }

endif;

add_action( 'widgets_init', 'uw_widgets_init' );


/**
 * Social Media Buttons
 *
 */
if ( ! function_exists( 'social_media' ) ):

  function social_media( $id = null ) 
  {
    echo get_social_media($id);
  }

endif;

if ( ! function_exists( 'get_social_media' ) ):

  function get_social_media( $id = null ) 
  {
    wp_register_script('social-media-js', get_bloginfo('template_url') . '/js/social-media.js', 'jquery');
    wp_enqueue_script('social-media-js');

    wp_register_style('social-media-css', get_bloginfo('template_url') . '/css/social-media.css', 'jquery');
    wp_enqueue_style('social-media-css');

    if ( $id == null ) {
      global $post;
      $permalink = get_permalink( $post->ID );
    } else if ($id == 'home') {
      $permalink = home_url();
    } else {
      $permalink = get_permalink( $id );
    }
    $url = (is_user_logged_in()) ? $permalink : 
                                   str_replace('.edu/cms/', '.edu/', $permalink );
    if ( !$post ) {
      $page_id = get_page_by_path( 'homepage' );
      $post = get_post($page_id);
    }

    $html = "<ul class='social-media'>" . 
              "<li class='fb'><a href='#'>" . 
              "<div class='facebook-like' data-href='$url' data-send='false' data-layout='button_count' data-width='30' data-show-faces='false'></div>" . 
              "</a></li>" . 
              "<li class='twit'>" .
              "<a class='twitter-share' href='http://twitter.com/share' data-url='$url'>Tweet</a>" .
              "</li>" . 
              "<li class='email'><a href='#' class='email-ajax' data-id='$post->ID'>Email</a></li>" . 
              "<li class='count' data-url='$url'></li>" . 
           "</ul>";
    return $html; 
  }

endif;

require( get_template_directory() . '/inc/patch-band-options.php' );
require( get_template_directory() . '/inc/media-credit.php' );
require( get_template_directory() . '/inc/custom-widgets.php' );
require( get_template_directory() . '/inc/custom-settings.php' );
require( get_template_directory() . '/inc/custom-image-sizes.php' );

class Walker_Navbar_Menu extends Walker_Nav_Menu {

	public $dropdown_enqueued;
  private $count  = 0;
  private $toggle = true;

	function __construct() {
		$this->dropdown_enqueued = wp_script_is( 'bootstrap-dropdown', 'queue' );
	}

	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

		if ( $element->current )
			$element->classes[] = 'active';

		$element->is_dropdown  = ( 0 == $depth ) && ! empty( $children_elements[$element->ID] );

		if ( $element->is_dropdown )
			$element->classes[] = 'dropdown';

		parent::display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output );
	}

	function start_lvl( &$output, $depth ) {

		if ( ! $this->dropdown_enqueued ) {
			wp_enqueue_script( 'bootstrap-dropdown' );
			$this->dropdown_enqueued = true;
		}

		$indent = str_repeat( "\t", $depth );
		$class  = ( 0 == $depth ) ? 'dropdown-menu' : 'unstyled';
		$output .= "\n{$indent}<ul class='{$class}'><div class='menu-wrap'>\n";
	}

	function end_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
    $div    = ( $this-> toggle ) ? '</div>' : '';
		//$output .= "$div$indent</ul>\n";
		$output .= "</div>$indent</div></ul>\n";
		//$output .= "close-$this->count$indent</ul>\n";
    $this->toggle = true;
    $this->count = 0;
	}

	function start_el( &$output, $item, $depth, $args ) {

		$item_html = '';
    if( $item->menu_item_parent && $this->count++ % 5 == 0) {
        //$item_html = ( $this->toggle ) ? 'open' : 'close open';
        $item_html = ( $this->toggle ) ? '<div class="menu-block">' : '</div><div class="menu-block">';
        //$this->toggle = !$this->toggle;
        $this->toggle = false;
    }
		parent::start_el( &$item_html, $item, $depth, $args );

		if ( $item->is_dropdown && ( 1 != $args->depth ) ) {

			$item_html = str_replace( '<a', '<a href="#open" class="dropdown-toggle" data-toggle="dropdown"', $item_html );
			$item_html = str_replace( '</a>', '<b class="caret"></b></a>', $item_html );
			$item_html = str_replace( esc_attr( $item->url ), '#', $item_html );
		}
    
		$output .= $item_html;
	}
  
}

?>
