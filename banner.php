<?php
  // Check to see if the header image has been removed
  $header_image = get_header_image();
  if ( ! empty( $header_image ) ) :
?>
<header style="background-image:url(<?php echo apply_filters('remove_cms', $header_image); ?>); <?php header_background_color(); ?>" id="branding" role="banner" <?php banner_class(); ?>>
<?php else: ?>
<header id="branding" role="banner" <?php banner_class(); ?>>
<?php endif; ?>


<div id="header">
		<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'twentyeleven' ); ?>"><?php _e( 'Skip to primary content', 'twentyeleven' ); ?></a></div>
		<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to sidebar content', 'twentyeleven' ); ?>"><?php _e( 'Skip to sidebar content', 'twentyeleven' ); ?></a></div>

		<a class="patch" href="http://www.uw.edu" title="University of Washington">University of Washington</a>
		<a class="wordmark" <?php custom_wordmark(); ?> href="<?php echo is_custom_wordmark() ? home_url('/') : '//www.washington.edu'; ?>">University of Washington</a>
		<a title="Show search" role="button" href="#searchicon-wrapper" id="searchicon-wrapper" class="visible-phone" aria-haspopup="true">Search</a>
		<div id="search">
			<form role="search" class="main-search" action="http://www.washington.edu/search" id="searchbox_008816504494047979142:bpbdkw8tbqc" name="form1">
				<span class="wfield">
					<input value="008816504494047979142:bpbdkw8tbqc" name="cx" type="hidden">
					<input value="FORID:0" name="cof" type="hidden">
          <label for="q" class="hide">Search the UW</label>
					<input id="q" class="wTextInput" placeholder="Search the UW" title="Search the UW" name="q" type="text" autocomplete="off">
  					<input value="Go" name="sa" class="formbutton" type="submit">
  				</span>
			</form>	

      <span class="search-toggle"></span>
      <div class="search-options">
        <label class="radio">
          <input type="radio" name="search-toggle" value="main" checked="checked" data-placeholder="the UW">
          UW.edu
        </label>
        <label class="radio">
          <input type="radio" name="search-toggle" value="directory" data-placeholder="the Directory"/>
          UW Directory
        </label>
        <?php if ( strpos( home_url(),'search') == false ): ?>
        <label class="radio">
          <input type="radio" name="search-toggle" value="site" data-site="<?php echo home_url('/'); ?>" data-placeholder="<?php bloginfo() ?>"/>
          This site
        </label>
        <?php endif; ?>

        <span class="search-options-notch"></span>
      </div>

      <!--div class="search-flash">
        <h6>Tip: Click the notch for search options</h6>
        <span class="search-options-notch"></span>
      </div-->

		</div>
		<a title="Show menu" role="button" href="#listicon-wrapper" id="listicon-wrapper" class="visible-phone" aria-haspopup="true">Menu</a>
</div>

<div id="thin-strip">
	<div>
		<ul role="navigation">
			<li><a href="http://www.washington.edu/">UW Home</a></li>
			<li><a href="http://www.washington.edu/home/directories.html">Directories</a></li>
			<li><a href="http://www.washington.edu/discover/visit/uw-events">Calendar</a></li>
			<li><a href="http://www.lib.washington.edu/">Libraries</a></li>
			<li><a href="http://www.washington.edu/maps">Maps</a></li>
			<li><a href="http://myuw.washington.edu/">My UW</a></li>
			<li class="visible-desktop"><a href="http://www.bothell.washington.edu/">UW Bothell</a></li>
			<li class="visible-desktop"><a href="http://www.tacoma.uw.edu/">UW Tacoma</a></li>
			<li class="visible-phone"><a href="http://www.uw.edu/news">News</a></li>
			<li class="visible-phone"><a href="http://www.gohuskies.com/">UW Athletics</a></li>
		</ul>
	</div>	
</div>
