<?php wp_footer(); ?> 


<div id="footerBG">
    <div id="footer">
    	<h2>Explore <?php echo apply_filters('abbreviation', get_bloginfo('title')); ?></h2>
    	<?php uw_footer_menu(); ?>
    </div>
</div>


<div id="footer-main">
  <div id="footer-right">
  	<a href="http://www.seattle.gov/" onclick="pageTracker._trackPageview('/pt/fn/seattle');">Seattle, Washington</a>
  </div>
	  <ul>
	  	<li><a href="http://www.washington.edu/home/siteinfo/form" onclick="pageTracker._trackPageview('/pt/fn/contact-us');">Contact Us</a></li>
	  	<li><a href="http://www.washington.edu/jobs" onclick="pageTracker._trackPageview('/pt/fn/employment');">Jobs</a></li>
	  	<li><a href="http://myuw.washington.edu/" onclick="pageTracker._trackPageview('/pt/fn/my-uw');">My UW</a></li>
	  	<li><a href="http://www.washington.edu/admin/rules/wac/rulesindex.html" onclick="pageTracker._trackPageview('/pt/fn/rules-docket');">Rules Docket</a></li>
	  	<li><a href="http://www.washington.edu/online/privacy" onclick="pageTracker._trackPageview('/pt/fn/privacy');">Privacy</a></li>
	  	<li><a href="http://www.washington.edu/online/terms" onclick="pageTracker._trackPageview('/pt/fn/terms');">Terms</a></li>
      </ul>
  <div id="footer-left">
  	<a href="http://www.washington.edu/" onclick="pageTracker._trackPageview('/pt/fn/copyright');">&copy; 2012 University of Washington</a>
  </div>
</div>


</body>
</html>
