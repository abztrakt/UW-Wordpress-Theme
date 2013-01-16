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

  register_widget('UW_RSS_Widget');
  unregister_widget('WP_Widget_RSS');

  register_widget('UW_Widget_CommunityPhotos');
  //register_widget('UW_Widget_Showcase_Links');
  register_widget('UW_Widget_Categories');
  register_widget('UW_Widget_Twitter');
  register_widget('UW_KEXP_KUOW_Widget');
  register_widget('UW_Showcase_Widget');
  register_widget('UW_Subpage_Menu');
  register_widget('UW_Nav_Menu_Widget');
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

    $show_popular = class_exists('GADWidgetData') && $instance['show-popular'];

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
      $blog_details = get_blog_details(get_current_blog_id());
      $path = str_replace('/cms', '', $blog_details->path);

      $start_date = date('Y-m-d', strtotime('-1 week', time()));
      $end_date   = date('Y-m-d', time());

      $login = new GADWidgetData();

      if($login->auth_type == 'oauth') {
        $ga = new GALib('oauth', NULL, $login->oauth_token, $login->oauth_secret, $login->account_id);
      } else {
        $ga = new GALib('client', $login->auth_token, NULL, NULL, $login->account_id);
      }

      $wp_posts = array();
      $pop_posts = $ga->pages_for_date_period($start_date, $end_date, $number+1);
      foreach ($pop_posts as $index=>$post) {
        if( $post['value'] == $path ) 
          unset($pop_posts[$index]);
        else {
          $wp_post = get_page_by_path(basename($post['value']), OBJECT, 'post');
          if (isset($wp_post) && !in_array($wp_post, $wp_posts)) {
            $wp_posts[] = $wp_post;
            $wp_post_views[$wp_post->ID] = $post['children']['children']['ga:pageviews'];
          }
        }
      }
      $pop_posts = array_slice($wp_posts, 0, $number ); // the first, most popular page, is always /news/ (the homepage)
    }
    

?>
		<?php echo $before_widget; ?>

      <?php  if ( $show_popular ) : ?>
    <ul id="news-tab-nav" data-tabs="toggle" tab-index="0">
        <li class="selected"><a class="recent-popular-widget" href="#tab-popular" title="Most popular">Most Popular</a></li>
        <li><a class="recent-popular-widget" href="#tab-recent" title="Most recent">Recent</a></li>
    </ul>
      <?php else: ?>
        <?php echo $before_title . $title . $after_title; ?>
      <?php endif; ?>
    
    <ul id="tab-recent" tab-index="0" class="recent-posts" <?php if( $show_popular ) : ?> style="display:none;" <?php endif; ?>>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
      <li>
        <?php if (has_post_thumbnail()) :  ?>
        <a class="widget-thumbnail" href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
          <?php the_post_thumbnail( 'thumbnail' ); ?>
        </a>
        <?php endif; ?>
        <a class="widget-link" href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
          <?php if ( get_the_title() ) the_title(); else the_ID(); ?>
        </a>
          <p> <small><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> ago</small> </p>
      </li>
		<?php endwhile; ?>
		</ul>

    <?php  wp_reset_postdata(); ?>

    <?php  if ( $show_popular ) : ?>

    <ul id="tab-popular" class="popular-posts" tab-index="0">

      <?php foreach( $pop_posts as $post ): ?>
          <li>
            <?php if (get_the_post_thumbnail($post->ID)) :  ?>
            <a class="widget-thumbnail" href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr($post->post_title); ?>">
              <?php echo get_the_post_thumbnail($post->ID, 'thumbnail'); ?>
            </a>
            <?php endif; ?>
            <a class="widget-link" href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr($post->post_title); ?>">
              <?php echo $post->post_title; ?>
            </a>
            <p><small><?php echo $wp_post_views[$post->ID]; ?> views</small></p>
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
		$instance['show-popular'] = (bool) $new_instance['show-popular'];
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


    <?php if (class_exists('GADWidgetData') ) : ?>
		<p> <input id="<?php echo $this->get_field_id('show-popular'); ?>" name="<?php echo $this->get_field_name('show-popular'); ?>" type="checkbox" value="1" <?php checked( $instance['show-popular']) ?> />
    <label for="<?php echo $this->get_field_id('show-popular'); ?>"><?php _e('Show popular posts'); ?></label> </p>
    <?php endif; ?>
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
 *
 * Instead of overriding the dropdown widget we will patch it by extending its class
 *
 *
 ***********************************************************************************/

class UW_Widget_Categories extends WP_Widget_Categories {

  	function widget( $args, $instance ) {
      extract( $args );

      $title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title'], $instance, $this->id_base);
      $c = ! empty( $instance['count'] ) ? '1' : '0';
      $h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
      $d = ! empty( $instance['dropdown'] ) ? '1' : '0';

      echo $before_widget;
      if ( $title )
        echo $before_title . $title . $after_title;

      $cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h, 'exclude' => '1');

      if ( $d ) {
        $cat_args['show_option_none'] = __('Select Category');
        wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
  ?>

  <script type='text/javascript'>
  /* <![CDATA[ */
        jQuery(document).ready(function($) {
          $("#cat").change(function() {
            var val = $(this).val();
            if ( val > 0 ) {
              location.href = "<?php echo home_url(); ?>/?cat="+val;
            }
          })
        });
  /* ]]> */
  </script>

  <?php
      } else {
  ?>
      <ul>
  <?php
      $cat_args['title_li'] = '';
      wp_list_categories(apply_filters('widget_categories_args', $cat_args));
  ?>
      </ul>
  <?php
      }

		echo $after_widget;
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

    $placeholder = get_bloginfo('template_url') . '/img/placeholder.gif';

    if (!is_wp_error( $rss ) ) { 
      $url = $rss->get_permalink();
      $maxitems = $rss->get_item_quantity(20); 

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
              <img data-src='$src' src='$placeholder' width='110' height='100' alt='$title'/>
            </span>
            <div style='width:110px'>
              <img data-src='$src' src='$placeholder' width='110' height='110' alt='$title'/>
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
      wp_register_script( 'jquery.communityphotos', get_bloginfo('template_url') . '/js/widget-communityphotos.js', array('jquery', 'jquery.waypoints') ) ;
      wp_enqueue_script( 'jquery.communityphotos' );
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
        <a href="https://www.twitter.com/<?php echo $instance['name'] ?>"><?php if ( ! empty( $title ) ) echo $before_title . $title . $after_title; ?></a>
        <div class="twitter-feed" data-name="<?php echo $name; ?>" data-count="<?php echo $count; ?>"></div>
      </div>
    <?php echo $after_widget;?>

  <?php endif; ?>

<?php  
	}
  function load_css_js() {
      wp_register_script( 'twitter-feed', get_bloginfo('template_url') . '/js/widget-twitter-feed.js' , 'jquery' );
      wp_enqueue_script( 'twitter-feed' );
      wp_enqueue_script( 'jquery.waypoints' );
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

        if (is_multisite())
            switch_to_blog(1);

        if (!empty($instance['category_id'])) {
            $arrPosts = get_posts(array('category'=>$instance['category_id']));
            // TODO Display multiple posts in category
            if (count($arrPosts) == 0)
                echo 'No Content';
            $strRand = rand(0, count($arrPosts) - 1);
            echo apply_filters('the_content', $arrPosts[$strRand]->post_content);
        } else {
            $post = get_post($instance['post_id']);
            echo apply_filters('the_content', $post->post_content);
        }
        if (is_multisite())
            restore_current_blog();

        if (is_super_admin() && ($instance['post_id']))
            echo '<a class="pull-right" target="_blank" href="' . $instance['edit'] . '">Edit</a>';
        
        echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

        if (is_multisite())
            switch_to_blog(1);

        if (!empty($new_instance['category_id'])) {
            $objCategory = get_category($new_instance['category_id']);
        } else {
            $post = get_post($new_instance['post_id']);
            $edit = get_edit_post_link($post->ID);
        }

        if (is_multisite())
            restore_current_blog();

        $instance['post_id'] = $new_instance['post_id'];
        $instance['category_id'] = $new_instance['category_id'];
        $instance['id'] = !empty($new_instance['category_id']) ? $new_instance['category_id'] : $new_instance['post_id'];

        $strTitle = !empty($new_instance['category_id']) ? $objCategory->name : $post->post_title;
		$instance['title'] = strip_tags( $strTitle );
		$instance['type']  = $new_instance['type'];
        $instance['edit']  = $edit ? $edit : '';


		return $instance;
	}

	public function form( $instance ) {
    wp_enqueue_script('jquery-ui-dialog');
    if (is_multisite())
        switch_to_blog(1);

    $cat = get_term_by('slug','showcase-widget', 'category');
    $args = array(
      'numberposts' => -1,
      'category' => $cat ? $cat->term_id : null
    );
    $posts = get_posts($args);
    $arrCats = get_categories(array('hide_empty' => 0,'pad_counts' => 1));
    if (is_multisite())
        restore_current_blog();
    
    $title  = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Showcase', '' );  ?>

		<input class="widefat hidden" disabled="disabled" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />

		<label for="<?php echo $this->get_field_id('post_id'); ?>"><?php _e( 'Choose content:' ); ?></label>
      <a class="alignright preview-showcase" id="preview-widget-<?php echo $this->get_field_id('post_id'); ?>" href="#preview">Preview</a>
      <select data-type="post" name="<?php echo $this->get_field_name('post_id'); ?>" id="<?php echo $this->get_field_id('post_id'); ?>" class="widefat showcase-select">
      <option value="">--</option>
      <?php foreach($posts as $post) : ?>
        <option value="<?php echo $post->ID; ?>"<?php selected( $instance['post_id'], $post->ID); ?>><?php _e($post->post_title); ?></option>
      <?php endforeach; ?>
      </select>

            <h3>OR</h3>

		<label for="<?php echo $this->get_field_id('category_id'); ?>"><?php _e( 'Choose category:' ); ?></label>
        <select data-type="category" name="<?php echo $this->get_field_name('category_id'); ?>" id="<?php echo $this->get_field_id('category_id'); ?>" class="widefat">
      <option value="">--</option>
      <?php foreach($arrCats as $objCat) : ?>
      <option value="<?php echo $objCat->cat_ID ?>" <?php selected( $instance['category_id'], $objCat->cat_ID); ?>><?php echo $objCat->name ?> (<?php echo $objCat->count ?>)</option>
      <?php endforeach; ?>
    </select>

        <input class="widefat hidden" disabled="disabled" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" type="text" value="<?php echo $instance['type']; ?>" />

      <style type="text/css">
        .preview-showcase-widget h2 {
          font-family: 'Open Sans', sans-serif; font-weight: 400; letter-spacing: -.05em; color: #39275B;
        }
        .preview-showcase-widget a {
          color: #3089C2;
          text-decoration:none;
        }
        .preview-showcase-widget li {
          margin-left: 0;
          margin-bottom: 5px;
          background: url('/cms/wp-content/themes/uw/img/bullet-gold.png') no-repeat left 7px transparent;
          list-style: none;
          padding-left: 12px;
         }
        .preview-showcase-widget p {
          font-size: 14px; line-height: 21px; 
          color: #333;
          font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
      </style>

      <?php foreach($posts as $post) : ?>

        <div class="hidden preview-showcase-widget post-<?php echo $post->ID; ?>">
          <h2><span><?php echo $post->post_title; ?></span></h2>
          <?php echo apply_filters('the_content', $post->post_content); ?>
        </div>

      <?php endforeach; ?>

      <script type="text/javascript">
        jQuery(document).ready(function($) {
          
                // set up a jquery boolean so this script only runs once on the page and not once per showcase widget
                if ( $.fn.showcase_widget_preview_enabled ) 
                  return;

                var length = $('select.showcase-select').first().find('option').length

                $('.preview-showcase-widget').slice(0,length)
                  .dialog({
                    autoOpen: false,
                    width:265,
                    modal:true
                 })

               $('body').on('click', 'a.preview-showcase', function() {
                 var $this = $(this)
                   , id    = $this.siblings('select').val()

                 $('.post-'+id).dialog('open');

               });

                // There is a better way to do this, but it works
                // Anytime a select changes, update it
                jQuery('select[data-type="category"]').change(function() {
                    $('option:selected', 'select[data-type="post"]').removeAttr('selected')
                });

                $('select[data-type="post"]').change(function() {
                    $('option:selected','select[data-type="category"]').removeAttr('selected')
                });

                $.fn.showcase_widget_preview_enabled = true;

        });
      </script>
		</p>
		<?php 
	}

} 

/**
 * UW RSS Widget 
 *  - Only difference between this and WP one is this shows images
 */
class UW_RSS_Widget extends WP_Widget {
	function UW_RSS_Widget() {
    $options = array( 'description' => 'Similar to the Wordpress RSS widget but allows a blurb before the RSS feed is listed.' );
		$control_ops = array('width' => 400, 'height' => 350);
		parent::WP_Widget( $id = 'uw_rss_widget', $name = 'UW RSS', $options , $control_ops );
	}
	function form($instance) {

    $default_inputs = array( 'url' => true, 'title' => true, 'items' => true, 'show_summary' => true, 'show_author' => true, 'show_date' => true, 'show_image' => true );
    $inputs = wp_parse_args( $inputs, $default_inputs );

    extract( $inputs, EXTR_SKIP);

    $number = esc_attr( $number );
    $title  = esc_attr( $title );
    $url    = esc_url( $url );
    $items  = (int) $items;
    if ( $items < 1 || 20 < $items )
      $items  = 10;
    $show_summary   = (int) $show_summary;
    $show_author    = (int) $show_author;
    $show_date      = (int) $show_date;

    if ( !empty($error) )
      echo '<p class="widget-error"><strong>' . sprintf( __('RSS Error: %s'), $error) . '</strong></p>';
  ?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Give the feed a title (optional):' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title']); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Featured blurb:' ); ?></label> 
		<textarea class="widefat" style="resize:vertical" rows="14" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Enter the RSS feed URL here:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $instance['url']); ?>" />
		</p>

    <p>
    <label for="<?php echo $this->get_field_id( 'items' ) ?>"><?php _e('Number of items to display:'); ?></label>
    <select id="<?php echo $this->get_field_id( 'items' ) ?>" name="<?php echo $this->get_field_name( 'items' ) ?>">
      <?php
          for ( $i = 1; $i <= 20; ++$i )
            echo "<option value='$i' " . selected( $instance['items'], $i ) . ">$i</option>";
      ?>
    </select>
    </p>

		<!--p>
      <input id="<?php echo $this->get_field_id( 'show_image' ); ?>" name="<?php echo $this->get_field_name( 'show_image' ); ?>" type="checkbox" value="1" <?php checked( $instance['show_image']); ?>/>
      <label for="<?php echo $this->get_field_id( 'show_image' ); ?>"><?php _e( 'Display item image?' ); ?></label> 
		</p>

		<p>
      <input id="<?php echo $this->get_field_id( 'show_summary' ); ?>" name="<?php echo $this->get_field_name( 'show_summary' ); ?>" type="checkbox" value="1" <?php checked( $instance['show_summary']); ?>/>
      <label for="<?php echo $this->get_field_id( 'show_summary' ); ?>"><?php _e( 'Display item content?' ); ?></label> 
		</p>
  
		<p>
      <input id="<?php echo $this->get_field_id( 'show_author' ); ?>" name="<?php echo $this->get_field_name( 'show_author' ); ?>" type="checkbox" value="1" <?php checked( $instance['show_author']); ?>/>
      <label for="<?php echo $this->get_field_id( 'show_author' ); ?>"><?php _e( 'Display item author?' ); ?></label> 
		</p>

		<p>
      <input id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" type="checkbox" value="1" <?php checked( $instance['show_date']); ?>/>
      <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display item date?' ); ?></label> 
		</p-->

<?php

  }

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['url']   = esc_url_raw(strip_tags( $new_instance['url'] ));
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['items'] = (int) ( $new_instance['items'] );
		$instance['show_image'] = (int) ( $new_instance['show_image'] );
		$instance['show_summary'] = (int) ( $new_instance['show_summary'] );
		$instance['show_author'] = (int) ( $new_instance['show_author'] );
		$instance['show_date'] = (int) ( $new_instance['show_date'] );

		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
    
		return $instance;
	}

	function widget($args, $instance) {
    extract($args);

    $title = apply_filters( 'widget_title', $instance['title'] );
    $text  = $instance['text'];
    $URL = $instance['url'];

    $content = '<span class="showcase-bar uw-rss-widget"></span>';

    if ( ! empty( $title ) ) $content .= $before_title . $title . $after_title;

    $content .= "<div class=\"featured\">$text</div>";


    if ( strlen($URL) > 0 ) {
    
      $rss = fetch_feed($URL);

      if (!is_wp_error( $rss ) ) { 
        $url = $rss->get_permalink();
        $maxitems = $rss->get_item_quantity($instance['items']); 

        $rss_items = $rss->get_items(0, $maxitems); 
        
        $content .= "<ul>";

        foreach ($rss_items as $index=>$item) {
          $title = $item->get_title();
          $link  = $item->get_link();
          $attr  = esc_attr(strip_tags($title));

          $content .= "<li><a href='$link' title='$attr'>$title</a></li>";
        }

        $content .= '</ul>';
        $content .= "<a class=\"rss-more\" href=\"$url\">More</a>";
      }
    }

    echo $before_widget . $content . $after_widget;
	}
}

/**
 *
 * Subpage Menu Widget - shows current page and all subpages
 *
 *
 ***********************************************************************************/
class UW_Subpage_Menu extends WP_Widget {

	public function UW_Subpage_Menu() {
		parent::__construct(
	 		'uw_subpage_menu',
			'Subpage Menu',
			array( 'classname' => 'subpage_menu', 'description' => __( "Displays a menu of child pages of the current page"), )
		);
    
	}

	public function widget( $args, $instance ) {
    //global $post;
    //if (!is_post_type_hierarchical($post->post_type)) 
      //return;
		extract( $args );
		$id    = $this->get_post_top_ancestor_id();
    $title = '<a href="' . get_permalink($id) .'" title="'. esc_attr(strip_tags(get_the_title($id))) .'">'.get_the_title($id).'</a>';

    echo $before_widget;?>

    <?php echo $before_title . $title . $after_title; ?>
    <?php echo '<ul class="menu">';?>
    <?php wp_list_pages( array('title_li'=>'','depth'=>1,'child_of'=>$id) ); ?>
    <?php echo '</ul>'; ?>
    <?php
		echo $after_widget;
	}

	public function form( $instance ) {

    //$title  = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( get_bloginfo('name'), '' );  ?>

		<!--p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p-->

		<?php 
	}

  function get_post_top_ancestor_id(){
      global $post;
      
      if($post->post_parent){
          $ancestors = array_reverse(get_post_ancestors($post->ID));
          return $ancestors[0];
      }
      
      return $post->ID;
  }
  
} 

/**
 * UW Custom Menu 
 *
 * Add's an anchor around the title that links to the homepage of the site
 * Only the display is custom, everything else is using the default WP_Nav_Menu_Widget
 */

class UW_Nav_Menu_Widget extends WP_Nav_Menu_Widget {

	function widget($args, $instance) {
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( !$nav_menu )
			return;

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . '<a href="'. home_url('/') .'" title="' . esc_attr(strip_tags($instance['title'])) . '">' . $instance['title'] .'</a>'. $args['after_title'];

		wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );

		echo $args['after_widget'];
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
