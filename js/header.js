jQuery(document).ready(function($){

  /**
   * Header Strip
   */

  var strip = $('#thin-strip')
      , win = $(window)
      , pos = $('body').hasClass('admin-bar') ? 28 : 0;
  
    $(window).bind('scroll', function() {

      var top = $(this).scrollTop()

      if ( win.width() < 768 ) {
        strip.removeAttr('style')
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
          var html = '<li><a href="http://www.atmos.washington.edu/weather/forecast/" title="Forecast is '+weat+'">';
          html += '<img src="/cms/wp-content/themes/uw/img/weather/top-nav/'+icon+'.png" alt="Forecast is '+weat+'"/>';
          html += '</a></li>';
          html += '<li><a href="http://www.atmos.washington.edu/weather/forecast/">';
          html += 'Seattle '+temp;
          html += '</a></li>';
          $('#thin-strip').find('ul').append(html)
        }
      });

});

$(window).load(function(){
	var alert = $('#alertMessage').outerHeight()
	$("body").css("background-position", "0 " + alert + "px" ); 
});

$(document).ready(function(){

  $('[href=#]').removeAttr('href'); //prevent ios nav bar from popping down
  
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

  // [TODO] clean up
    
  var searchbtn = $("#searchicon-wrapper");
	searchbtn.click(function(e){
   		e.preventDefault();
		search.toggleClass("activate");
    	// $('#search form').css('display', 'block');	
		if(topnav.hasClass('activate')){
			topnav.toggleClass("activate");	
		}	
		$('input.wTextInput').focus(); // Focus in search field		
		return false;	
	});	
	
  var menubtn= $("#listicon-wrapper");
	menubtn.click(function(e){
    	e.preventDefault();
		topnav.toggleClass("activate");		
		// $('#thin-strip ul').css('display', 'block');
		if(search.hasClass('activate')){
			search.toggleClass("activate");	
		}	
		$('#thin-strip ul a:first').focus()	 // Focus in the dropdown field	
		return false;
	});

});
