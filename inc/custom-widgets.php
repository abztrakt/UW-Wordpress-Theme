<?php
/**
 * 
 * Register all of the UW custom widgets
 * Remove unwanted widgets
 * Also acts as a list of custom widgets
 */
function uw_register_widgets() {
	if ( !is_blog_installed() )
		return;

	register_widget('UW_Widget_Recent_Posts');
  unregister_widget('WP_Widget_Recent_Posts');

	register_widget('UW_Widget_YouTube_Playlist');
	register_widget('UW_Widget_MailChimp');
  register_widget('UW_Widget_CommunityPhotos');
  register_widget('UW_Widget_Showcase_Links');
  register_widget('UW_Widget_Twitter');
  register_widget('UW_KEXP_KUOW_Widget');
  register_widget('UW_Showcase_Widget');
}

add_action('widgets_init', 'uw_register_widgets', 1);


/**
 *
 *
 * Updated Recent Posts widget that includes the featured image
 *
 *
 **************************************************************/

class UW_Widget_Recent_Posts extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent posts on your site") );
		parent::__construct('recent-posts', __('Recent Posts'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_posts', 'widget');

    $show_popular = class_exists('GADWidgetData');

		if ( !is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) :

    if ( $show_popular ) {

      $start_date = date('Y-m-d', strtotime('-1 week', time()));
      $end_date   = date('Y-m-d', time());

      $login = new GADWidgetData();

      if($login->auth_type == 'oauth') {
        $ga = new GALib('oauth', NULL, $login->oauth_token, $login->oauth_secret, $login->account_id);
      } else {
        $ga = new GALib('client', $login->auth_token, NULL, NULL, $login->account_id);
      }

      $pop_posts = $ga->pages_for_date_period($start_date, $end_date, $number);
      $pop_posts = array_slice($pop_posts, 0, $number ); // the first, most popular page, is always /news/ (the homepage)
    }
    

?>
		<?php echo $before_widget; ?>

    <ul id="news-tab-nav" data-tabs="toggle">
      <?php  if ( $show_popular ) : ?>
        <li class="selected"><a class="recent-popular-widget" href="#tab-popular" title="Most popular">Most Popular</a></li>
      <?php endif; ?>

      <li <?php if( !$show_popular ): ?> class="selected" <?php endif; ?>><a class="recent-popular-widget" href="#tab-recent" title="Most recent">Recent</a></li>
    </ul>
    
    <ul id="tab-recent" class="recent-posts" <?php if( class_exists('GADWidgetData')) : ?> style="display:none;" <?php endif; ?>>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
      <li>
        <?php if (has_post_thumbnail()) :  ?>
        <a class="widget-thumbnail" href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
          <?php the_post_thumbnail( 'Thimble' ); ?>
        </a>
        <?php endif; ?>
        <a class="widget-link" href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
          <?php if ( get_the_title() ) the_title(); else the_ID(); ?>
        </a>
      </li>
		<?php endwhile; ?>
		</ul>

    <?php  wp_reset_postdata(); ?>

    <?php  if (class_exists('GADWidgetData') ) : ?>

    <ul id="tab-popular" class="popular-posts">

      <?php foreach( $pop_posts as $ga_post ): ?>
        <?php $post = get_page_by_path(basename($ga_post['value']), OBJECT, 'post'); ?>
        <?php if (!isset($post)) continue; ?>

          <li>
            <?php if (get_the_post_thumbnail($post->ID)) :  ?>
            <a class="widget-thumbnail" href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr($post->post_title); ?>">
              <?php echo get_the_post_thumbnail($post->ID, 'Thimble'); ?>
            </a>
            <?php endif; ?>
            <a class="widget-link" href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr($post->post_title); ?>">
              <?php echo $post->post_title; ?>
            </a>
            <p><small><?php echo $ga_post['children']['children']['ga:pageviews']; ?> views</small></p>
          </li>

      <?php endforeach; ?>
    </ul>

    <?php endif; ?>

		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}

/**
 *
 *
 * Instead of overriding the link widget we will patch it via filter's it provides
 *
 *
 ***********************************************************************************/

function uw_patch_link_widget( $args ) {
  $category = get_term_by('id', $args['category'], 'link_category');
  $args['title_before'] = '<h2 class="widgettitle"><span>';
  $args['title_after'] = '</span></h2>';
  $args['category_before'] = '<div id="%id" class="%class">';
  $args['category_after'] = '</div>';
  $args['class'] .= " widget widget_links $category->slug";
  return $args;
}

add_filter('widget_links_args', 'uw_patch_link_widget');



/**
 *
 *
 * New YouTube Playlist widget
 *
 *
 ***********************************************************************************/
class UW_Widget_YouTube_Playlist extends WP_Widget {

	public function UW_Widget_YouTube_Playlist() {
		parent::__construct(
	 		'widget_youtube_playlist',
			'YouTube Playlist',
			array( 'classname' => 'widget_youtube_playlist', 'description' => __( "Put your YouTube playlist into your page"), )
		);

   if ( is_active_widget(false, false, $this->id_base) )
      add_action( 'wp_head', array(&$this, 'youtube_playlist_js') );
    
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

    echo str_replace('span4','span8',$before_widget);?>

    <?php if ( ! empty( $title ) ) echo $before_title . $title . $after_title; ?>

      <div id="nc-video-player">
        <div id="tube-wrapper">
          <div id="youtubeapi" data-pid="<?php echo $instance['playlist_id']; ?>"></div>
          <div id="vidSmall">
            <div class="scrollbar">
            <div class="track">
            <div class="thumb">
            <div class="end">
            </div></div></div></div>
            <div class="viewport">
            <div id="vidContent" class="overview">
            </div></div>
          </div>
        </div>
      </div>

    <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['playlist_id'] = strip_tags( $new_instance['playlist_id'] );

		return $instance;
	}

	public function form( $instance ) {

		$title  = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Videos', '' ); 
		$id     = isset( $instance[ 'playlist_id' ] ) ?  $instance[ 'playlist_id' ] :  __( '', '' ); ?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'playlist_id' ); ?>"><?php _e( 'Playlist ID:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'playlist_id' ); ?>" name="<?php echo $this->get_field_name( 'playlist_id' ); ?>" type="text" value="<?php echo esc_attr( $id ); ?>" />
		</p>

		<?php 
	}

  public function youtube_playlist_js() {
    wp_register_script('youtube-playlist-widget', get_bloginfo('template_directory') . '/js/widget-youtube-playlist.js', 'swfobject');
    wp_enqueue_script( 'swfobject' );
    wp_enqueue_script( 'youtube-playlist-widget');
  }

} 


/**
 *
 *
 * New MailChimp Widget for UW News
 * [todo] this may be deleted if we want to use an official Mailchimp widget instead
 *
 *
 ***********************************************************************************/
class UW_Widget_MailChimp extends WP_Widget {

	public function UW_Widget_MailChimp() {
		parent::__construct(
	 		'widget_mailchimp_subscribe',
			'MailChimp Subsciption',
			array( 'classname' => 'widget_mailchimp_subscribe', 'description' => __( "Have User's sign up to your weekly and daily MailChimp campaigns"), )
		);

   if ( is_active_widget(false, false, $this->id_base) )
      add_action( 'wp_head', array(&$this, 'mailchimp_js') );
    
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

    echo $before_widget;?>

    <div id="subscribe-box">
      <?php if ( ! empty( $title ) ) echo $before_title . $title . $after_title; ?>
      <form id="mailchimp">
        <input type="text" name="email" id="email" placeholder="Your email address" class="subscribeEmailText" />
        <input class="btn" type="submit" name="submit" value="Submit" />

        <div>
        <input type="radio" name="pref" checked="checked" value="UW News Weekly Roundup" id="pref_weekly" />
        <label class="label-marg" for="pref_weekly">Weekly Roundup</label>

        <input type="radio" name="pref" value="UW Today" id="pref_daily" />
        <label for="pref_daily">Daily</label>
        </div>
        
        <div class="response"></div>
      </form>
    </div>


    <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {

    $title  = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Email Campaign', '' );  ?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<?php 
	}

  public function mailchimp_js() {
    wp_register_script('mailchimp-subscribe-widget', get_bloginfo('template_directory') . '/js/widget-mailchimp-subscribe.js');
    wp_enqueue_script( 'mailchimp-subscribe-widget' );
  }

} 

/**
 *
 *
 * New Community Photos Widget
 *
 *
 ***********************************************************************************/
class UW_Widget_CommunityPhotos extends WP_Widget {
	function UW_Widget_CommunityPhotos() {
		// widget actual processes
		parent::WP_Widget( $id = 'community_photos', $name = 'Community Photos', $options = array( 'description' => 'Display the UW Community Photos' ) );
    if ( is_active_widget(false, false, $this->id_base) ) {
      add_action( 'wp_head', array(&$this, 'load_css') );
    }

	}
	function form($instance) {
    $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Community Photos'; ?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	function widget($args, $instance) {
    extract( $args );
		// outputs the content of the widget
    $URL = 'http://depts.washington.edu/newscomm/photos/feed';
    $rss = fetch_feed($URL);
		$title = apply_filters( 'widget_title', $instance['title'] );

    if (!is_wp_error( $rss ) ) { // Checks that the object is created correctly 
      // Figure out how many total items there are, but limit it to 5. 
      $url = $rss->get_permalink();
      $maxitems = $rss->get_item_quantity(20); 

      // Build an array of all the items, starting with element 0 (first element).
      $rss_items = $rss->get_items(0, $maxitems); 
      
      $content = '<span class="showcase-bar community-photos"></span><div class="communityphotos">';
      if ( ! empty( $title ) ) $content .= $before_title . $title . $after_title;
      foreach ($rss_items as $item) {
        $title = $item->get_title();
        $link = $item->get_link();
        $src = ereg_replace("(https?)://", "//",$item->get_enclosure()->get_link());
        $content .= "
          <a href='$link' title='$title'>
            <span>
              <img src='$src' width='110' height='100' alt='$title'/>
            </span>
            <div style='width:110px'>
              <img src='$src' width='110' height='110' alt='$title'/>
              <p>View Full Size</p>
            </div>
          </a>
        ";
      }
      $content .= "<span><a class='more' href='http://depts.washington.edu/newscomm/photos/'>More</a>
                 <a class='more' href='http://depts.washington.edu/newscomm/photos/wp-admin/edit.php'>Submit your photos</a></span>
                 </div>";

      echo $before_widget . $content . $after_widget;
    }
	}
  function load_css() {
      wp_register_style( 'communityphotos', get_bloginfo('template_url') . '/css/communityphotos.css' );
      wp_enqueue_style( 'communityphotos' );
  }
}


/**
 *
 *
 * Showcase Links Widget
 *
 *
 ***********************************************************************************/
class UW_Widget_Showcase_Links extends WP_Widget {

	public function UW_Widget_Showcase_Links() {
		parent::__construct(
	 		'widget_showcase_links',
			'Showcase Links',
			array( 'classname' => 'widget_showcase_links', 'description' => __( "Showcase your links" ), )
		);

   //if ( is_active_widget(false, false, $this->id_base) )
      //add_action( 'wp_head', array(&$this, 'mailchimp_js') );
    
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
    $category = $instance['category'];

    echo $before_widget;?>

    <div class="sub-block">
      <span class="showcase-bar <?php echo sanitize_title($instance['title']); ?>"></span>
      <?php if ( ! empty( $title ) ) echo $before_title . $title . $after_title; ?>
      <?php $links = get_bookmarks("category=$category&orderby=rating"); ?>

    <?php foreach($links as $index=>$link) : ?>
      <?php list($title, $source) = explode('-', $link->link_name); ?>
      <?php if($index == 0) :  ?>

      <a href="<?php echo $link->link_url;?>"> <img src="<?php echo $link->link_image; ?>"/> </a>
      <p>
        <a href="<?php echo $link->link_url;?>"> <?php echo $title; ?></a> -
        <?php echo $link->link_description; ?>
        <a href="<?php echo $link->link_url;?>"> <?php echo $source; ?></a>
      </p>
      <ul>

      <?php else: ?>

        <li><a href="<?php echo $link->link_url; ?>"><?php echo $link->link_name; ?></a></li>


      <?php endif; ?>

    <?php endforeach; ?>
      </ul>
    </div>

    <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['category'] = intval($new_instance['category']);

		return $instance;
	}

	public function form( $instance ) {

  	$link_cats = get_terms( 'link_category');
    $title  = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Showcased Links', '' );  ?>

		<p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
      <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select link category to display:'); ?></label>
      <select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">


      <?php foreach ( $link_cats as $link_cat ) {
        echo '<option value="' . intval($link_cat->term_id) . '"'
          . ( $link_cat->term_id == $instance['category'] ? ' selected="selected"' : '' )
          . '>' . $link_cat->name . "</option>\n";
      } ?>

      </select>
		</p>


		<?php 
	}

} 

/**
 *
 * Twitter widget
 *
 */

class UW_Widget_Twitter extends WP_Widget {
	function UW_Widget_Twitter() {
		// widget actual processes
		parent::WP_Widget( $id = 'twitter_feed', $name = 'Twitter Feed', $options = array( 'description' => 'Display your latest tweets' ) );
    if ( is_active_widget(false, false, $this->id_base) ) {
      add_action( 'wp_head', array(&$this, 'load_css_js') );
    }

	}
	function form($instance) {
    $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Twitter Feed';
    $name  = isset($instance['name']) ? esc_attr($instance['name']) : 'twitter';
    $count = isset($instance['count']) ? esc_attr($instance['count']) : 5; 
?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e( 'Twitter screen name:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of tweets to show:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
		</p>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['name'] = strip_tags( $new_instance['name'] );
		$instance['count'] = intval( $new_instance['count'] );
		return $instance;
	}

	function widget($args, $instance) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
    $name  = $instance['name'];
    $count = $instance['count'];
?>

  <?php if ($name != '' && $count > 0 ): ?>

    <?php echo $before_widget; ?>
      <div class="twitter-box">
        <a href="//twitter.com/<?php echo $instance['name'] ?>"><?php if ( ! empty( $title ) ) echo $before_title . $title . $after_title; ?></a>
        <div class="twitter-feed" data-name="<?php echo $name; ?>" data-count="<?php echo $count; ?>"></div>
      </div>
    <?php echo $after_widget;?>

  <?php endif; ?>

<?php  
	}
  function load_css_js() {
      wp_register_script( 'twitter-feed', get_bloginfo('template_url') . '/js/widget-twitter-feed.js' , 'jquery' );
      wp_enqueue_script( 'twitter-feed' );
  }
}


/**
 *
 * KEXP/KUOW Widget
 *
 ******************************************/

class UW_KEXP_KUOW_Widget extends WP_Widget {

	function UW_KEXP_KUOW_Widget() {
		$widget_ops = array( 'description' => __('The latest news from KEXP and KUOW') );
		parent::__construct( 'kexp-kuow', __('KEXP/KUOW'), $widget_ops );
	}

	function widget($args, $instance) {

		if ( isset($instance['error']) && $instance['error'] )
			return;

		extract($args, EXTR_SKIP);

    $kexp_url = 'http://blog.kexp.org/feed/';
    $kuow_url = 'http://feeds2.feedburner.com/KUOW';

		$kexp = fetch_feed($kexp_url);
		$kuow = fetch_feed($kuow_url);

		$url = esc_url(strip_tags($kexp_url));
    $title = "<ul id='radio-tab-nav' data-tabs='toggle'>
                <li class='selected'><a class='rsswidget' href='#tab-kexp' title='KEXP'>KEXP</a></li>
                <li><a class='rsswidget' href='#tab-kuow' title='KUOW'>KUOW</a></li>
              </ul>";


		echo $before_widget;
    echo '<div class="kexp-kuow">';
    echo $title;
    echo '<div class="radio-tab-content" id="tab-kexp"><span>';
		wp_widget_rss_output( $kexp, $instance );
    echo "<a href='$kexp_url' class='more'>More</a>";
    echo '</span></div>';
    echo '<div class="radio-tab-content" id="tab-kuow" style="display:none;"><span>';
		wp_widget_rss_output( $kuow, $instance );
    echo "<a href='$kuow_url' class='more'>More</a>";
    echo '</span></div>';
    echo '</div>';
		echo $after_widget;

		if ( ! is_wp_error($kexp) )
			$kexp->__destruct();
		unset($kexp);

		if ( ! is_wp_error($kuow) )
			$kuow->__destruct();
		unset($kuow);
	}

	function update($new_instance, $old_instance) {
		$testurl = ( isset( $new_instance['url'] ) && ( !isset( $old_instance['url'] ) || ( $new_instance['url'] != $old_instance['url'] ) ) );
		return wp_widget_rss_process( $new_instance, $testurl );
	}

	function form($instance) {

		if ( empty($instance) )
			$instance = array( 'title' => '', 'url' => '', 'items' => 10, 'error' => false, 'show_summary' => 0, 'show_author' => 0, 'show_date' => 0 );

		$instance['number'] = $this->number;

		//wp_widget_rss_form( $instance );
	}
}

/**
 *
 * UW Showcase widget
 *   - This is the Uber , cross site widget
 */

class UW_Showcase_Widget extends WP_Widget {

	public function UW_Showcase_Widget() {
		parent::__construct(
	 		'uw_showcase_widget',
			'UW Showcase',
			array( 'classname' => 'widget_uw_showcase', 'description' => __( "Display a UW Showcase widget on your site") )
		);

	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

    echo $before_widget;

    if ( ! empty( $title ) ) echo $before_title . $title . $after_title;

    echo apply_filters('the_content', $instance['content']); 

    if (is_super_admin()) 
      echo '<a class="pull-right" target="_blank" href="' . $instance['edit'] . '">Edit</a>';
    
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
    switch_to_blog(1);
    $post = get_post($new_instance['id']);
    $edit = get_edit_post_link($post->ID);
    restore_current_blog();

		$instance['id'] = $new_instance['id'];
		$instance['title']   = strip_tags( $post->post_title );
		$instance['content'] = $post->post_content;
    $instance['edit'] = $edit;

		return $instance;
	}

	public function form( $instance ) {
    $cat = get_term_by('slug','showcase-widget', 'category');
    $args = array(
      'numberposts' => -1,
      'category' => $cat->term_id
    );
    switch_to_blog(1);
    $posts = get_posts($args);
    restore_current_blog();
    
    $title  = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Showcase', '' );  ?>

		<input class="widefat hidden" disabled="disabled" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />

    <p>
		<label for="<?php echo $this->get_field_id('id'); ?>"><?php _e( 'Choose content:' ); ?></label>
			<select name="<?php echo $this->get_field_name('id'); ?>" id="<?php echo $this->get_field_id('id'); ?>" class="widefat">

      <?php foreach($posts as $post) : ?>
        <option value="<?php echo $post->ID; ?>"<?php selected( $instance['id'], $post->ID); ?>><?php _e($post->post_title); ?></option>
      <?php endforeach; ?>

			</select>
		</p>
		<?php 
	}

} 






/**
 * Add span to titles
 */
function uw_add_spans_to_widget_titles($title) {
  return "<span>$title</span>";
}
add_filter('widget_title', 'uw_add_spans_to_widget_titles');

?>
