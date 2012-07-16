<?php
  // Check to see if the header image has been removed
  $header_image = get_header_image();
  if ( ! empty( $header_image ) ) :
?>
<header style="background-image:url(<?php header_image(); ?>)" id="branding" role="banner" <?php banner_class(); ?>>
<?php else: ?>
<header id="branding" role="banner" <?php banner_class(); ?>>
<?php endif; ?>


<div id="header">
		<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'twentyeleven' ); ?>"><?php _e( 'Skip to primary content', 'twentyeleven' ); ?></a></div>
		<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to sidebar content', 'twentyeleven' ); ?>"><?php _e( 'Skip to sidebar content', 'twentyeleven' ); ?></a></div>

		<a class="patch" href="http://www.uw.edu" alt="150 Years">150 Years</a>
		<a class="wordmark" <?php custom_wordmark(); ?> href="<?php echo is_custom_wordmark() ? home_url('/') : '//www.washington.edu'; ?>">University of Washington</a><span id="listicon-wrapper"></span><span id="searchicon-wrapper"></span>
		<div id="search">
			<form role="search" class="main-search" action="http://www.washington.edu/search" id="searchbox_008816504494047979142:bpbdkw8tbqc" name="form1">
				<span class="wfield">
					<input value="008816504494047979142:bpbdkw8tbqc" name="cx" type="hidden">
					<input value="FORID:0" name="cof" type="hidden">
          <label for="q" class="hide">Search the UW</label>
					<input class="wTextInput" placeholder="Search the UW" title="Search the UW" name="q" type="text">
  					<input onclick="pageTracker._trackPageview('/pt/hn/search-action');" value="Go" name="sa" class="formbutton" type="submit">
  				</span>
			</form>	
		</div>
</div>

<div id="thin-strip">
	<div>
		<ul role="global links">
			<li><a href="http://www.washington.edu/">UW Home</a></li>
			<li><a href="http://www.washington.edu/home/directories.html">Directories</a></li>
			<li><a href="http://www.washington.edu/discover/visit/uw-events">Calendar</a></li>
			<li><a href="http://www.lib.washington.edu/">Libraries</a></li>
			<li><a href="http://www.washington.edu/maps">Maps</a></li>
			<li><a href="http://myuw.washington.edu/">My UW</a></li>
			<li class="visible-desktop"><a href="http://bothell.washington.edu/">UW Bothell</a></li>
			<li class="visible-desktop"><a href="http://tacoma.washington.edu/">UW Tacoma</a></li>
			<li class="visible-phone"><a href="http://www.uw.edu/news">News</a></li>
			<li class="visible-phone"><a href="http://www.gohuskies.com/">UW Athletics</a></li>
		</ul>
	</div>	
</div>
