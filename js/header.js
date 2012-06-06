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

    })

});



//
//
// Mobile header outdated stuff. Dane, have at it.
//
//
//



$(window).load(function(){
	var alerty = $('#alertMessage').outerHeight()
	$("body").css("background-position", "0 " + alerty + "px" ); 
 
  
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
    search.css('visibility', 'visible');
		if(topnav.hasClass('activate')){
			topnav.toggleClass("activate");		
		}			
		return false;
	});
	
  var menubtn= $("#listicon-wrapper");
	menubtn.click(function(e){
    e.preventDefault();
		topnav.toggleClass("activate");		
    topnav.css('visibility', 'visible');
		if(search.hasClass('activate')){
			search.toggleClass("activate");		
		}		
		return false;
	});
	
  // Accessibility 
/*
	topnav.css('visibility','hidden').bind('webkitTransitionEnd', function() {
    if ( !topnav.hasClass('activate') && !search.hasClass('activate') || search.hasClass('activate')) {
       topnav.css('visibility','hidden');
    }   
  });
  search.css('visibility','hidden').bind('webkitTransitionEnd', function() {
    if ( !topnav.hasClass('activate') && !search.hasClass('activate') || topnav.hasClass('activate')) {
       search.css('visibility','hidden');
    }
  });
*/

});


