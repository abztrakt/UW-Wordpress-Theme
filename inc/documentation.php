<?php
// Create the function to output the contents of our Dashboard Widget


function uw_add_documentation_dashboard_widget() {

	global $wp_meta_boxes;
  
	wp_add_dashboard_widget('uw-documentation', 'Documentation and FAQs', 'uw_documentation_html');	

  $wp_meta_boxes['dashboard']['side']['core']['uw-documentation'] =
     $wp_meta_boxes['dashboard']['normal']['core']['uw-documentation'];
  unset($wp_meta_boxes['dashboard']['normal']['core']['uw-documentation']);
  
  remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
  
} 

add_action('wp_dashboard_setup', 'uw_add_documentation_dashboard_widget' ); 

function uw_documentation_html() 
{
  ?>

	<div class="wrap">

    <p>
      This documentation is also available on the <a href="http://www.washington.edu/marketing/web-design/wordpress-theme/documentation/" target="_blank">University Marketing</a> site.
    </p>

      <h2>Basic Information</h2>
      <p>
        For new-user documentation such as how to edit pages or make a post, please visit the 
        <a href="http://codex.wordpress.org/Main_Page">WordPress Codex</a>.
      </p>

      <h2>Features &amp; Tips</h2>

      <ul class="shortcode-blogroll">
        <li><a target="_blank" href=
        "http://www.washington.edu/marketing/2013/01/02/embed-the-campus-map/">Embed the
        Campus Map</a></li>

        <li><a target="_blank" href="http://www.washington.edu/marketing/2012/12/27/google-calendar/">Embed a
        Google Calendar</a></li>

        <li><a target="_blank" href="http://www.washington.edu/marketing/2012/12/19/adding-users/">Add Users
        and Choose User Roles</a></li>

        <li><a target="_blank" href=
        "http://www.washington.edu/marketing/2012/12/19/how-to-insert-images-into-posts-and-pages/">
        Insert Images into Posts and Pages</a></li>

        <li><a target="_blank" href=
        "http://www.washington.edu/marketing/2012/12/19/commenting-code/">Commenting Code in
        the WordPress Editor</a></li>

        <li><a target="_blank" href=
        "http://www.washington.edu/marketing/2012/12/19/pagelets/">Pagelets</a></li>

        <li><a target="_blank" href=
        "http://www.washington.edu/marketing/2012/12/19/blogroll-shortcode/">[blogroll]:
        Embed Blog Posts on Pages</a></li>

        <li><a target="_blank" href="http://www.washington.edu/marketing/2012/12/19/rss-feeds/">How to Find
        and Embed RSS Feeds</a></li>

        <li><a target="_blank" href="http://www.washington.edu/marketing/2012/12/19/add-captions/">Add
        Captions to Images or Video</a></li>

        <li><a target="_blank" href="http://www.washington.edu/marketing/2012/12/19/infobox/">Create an
        InfoBox</a></li>
      </ul>

      <h2>Additional Information</h2>

      <p>
        If you need guidance with something that is not outlined above or in the <a href= "http://codex.wordpress.org/Main_Page">WordPress Codex</a>,
        please contact <a href="mailto:uweb@uw.edu">uweb@uw.edu</a> for help.
      </p>

	</div>
  
<?php
}
