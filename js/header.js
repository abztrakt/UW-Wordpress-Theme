jQuery(document).ready(function($){

  //prevent ios nav bar from popping down
  $('[href=#]').removeAttr('href'); 

  /**
   * Header Strip
   */
  var strip = $('#thin-strip')
      , win = $(window)
      , pos = $('body').hasClass('admin-bar') ? 28 : 0;
  
    $(window).bind('scroll', function() {

      var top = $(this).scrollTop()

      if ( win.width() < 768 ) {
        //strip.removeAttr('style')
        return false
      }

      if ( top < 160 - pos )
        strip.stop().css({'position':'absolute', 'top':0}).removeClass('thin-fixed').data('animate', false);

      if ( top > 220 && !strip.is(':animated') && !strip.data('animate')) 
        strip.css({'position':'fixed', 'top': -30}).addClass('thin-fixed').animate( {'top': pos},300).data('animate', true);

    });

    /**
     * Header weather widget
     */
      var data = {
        q:'http://www.atmos.washington.edu/rss/home.rss',
        v:'2.0'
      }
      $.ajax({
        url: 'https://ajax.googleapis.com/ajax/services/feed/load?callback=?',
        dataType: 'jsonp',
        data: data,
        success: function(json) { 
          var icon = $.trim(json.responseData.feed.entries[2].title.split('|')[1]);
          var weat = $.trim(json.responseData.feed.entries[1].title.split('|')[1]);
          var temp = $.trim(json.responseData.feed.entries[0].title.split('|')[1]);
          var html = '<li class="header-weather"><a href="http://www.atmos.washington.edu/weather/forecast/" title="Forecast is '+weat+'">';
          html += '<img src="/news/wp-content/themes/uw/img/weather/top-nav/'+icon+'.png" alt="Forecast is '+weat+'"/>';
          html += '</a></li>';
          html += '<li class="header-forcast"><a href="http://www.atmos.washington.edu/weather/forecast/">';
          html += 'Seattle '+temp;
          html += '</a></li>';
          $('#thin-strip').find('ul').append(html)
        }
      });

  var lip = $('#lip'),
      linkRotator = $('#linkRotator'),
      ul = linkRotator.find('ul').first(),
      linkImage = $('#linkArrowIcon'),
      topnav = $('#thin-strip'),
      search = $('#search form'),
      links = linkImage.attr('src'),
      closeImage = $('#menuClose');

	ul.hide();
	linkImage.click(function(){
	    ul.show();
		var height = (linkRotator.height() != 0) ? 0 : ul.outerHeight();
		linkImage.addClass('hideLinkAnchor').one('webkitTransitionEnd', function() {
			  linkRotator.toggleClass('rotateLip').css('height', height);
		});
		return false;
	});
	
	closeImage.click(function(){
		if (linkRotator.height() != 0){
			linkRotator.css('height', 0).one('webkitTransitionEnd', function() {				
				ul.hide();
				linkImage.removeClass('hideLinkAnchor');
			});
		} 
		return false;
	});


  $('#q').on('focus', function() {
    window.scrollTo(0,0)
  })

  if ( win.width() < 768 ) {
    search.css('visibility','hidden')
    topnav.css('visibility','hidden')
  }

  $('body').on('touchstart click', '#searchicon-wrapper, #listicon-wrapper', function() {
    var $this = $(this)
      , $nav  = [search, topnav]
      , ismenu = $this.is('#listicon-wrapper') 

    if ( ismenu ) 
      $nav.reverse()

    search.find('input.wTextInput').blur()

    var height = $nav[0].data('open') ? 0 : 
                 ismenu ? 340 : 45;

    $nav[0]
      .css('visibility', 'visible')
      .height(height)
      .data('open',!$nav[0].data('open'))

    $nav[1]
      .height(0)
      .data('open', false )

    // Toggle title Show/Hide text 
    $this.attr('title', $this.attr('title').indexOf('Show') ===  -1 ? 
                          $this.attr('title').replace('Hide', 'Show') :
                          $this.attr('title').replace('Show', 'Hide') )

    // if search is clicked
    if ( !ismenu && search.data('open')) {
      search.find('input.wTextInput').focus()
      window.scrollTo(0,0)
    }

    return false; 

  }).on('transitionend webkitTransitionEnd mozTransitionEnd oTransitionEnd', '#thin-strip, form.main-search', function(e) {

    var $this = $(this)

    if ( !$this.data('open') && !$this.height() )
      $this.css('visibility','hidden')

  })

  $(window).resize(function() {
    if ( $(this).width() > 767 ) {
      search.removeAttr('style') 
      topnav.removeAttr('style') 
    }
  })


});

jQuery(window).load(function() {
  // hide mobile safari url bar
  setTimeout(function(){
    window.scrollTo(0, 0);
  }, 0);
});
