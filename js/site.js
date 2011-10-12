jQuery(document).ready(function($){

  var getpage = function() {
      if(Modernizr.history) {
        history.pushState({ path: this.path }, '', this.href)
        $.get(this.href, function(data){
          $data = $(data);
          $('#primary').fadeOut(200, function() {
            $(this).replaceWith($data.find('#primary').hide().fadeIn(200))
          })
          var new_img = $data.find('a.banner').children('img').hide();
          new_img.bind('load', function() { 
            $(this).unbind('load');
            $('.banner').append(new_img)
              .children('img').first()
              .fadeOut(200, function() {
                $(this).children('img').first().remove();
              });
            $(this).fadeIn(200);
          });

        });
        return false;
      }
  }

  /* Left navigation - 
   *   clones a link as the first link in 
   *   the accordion
   */
  var menu = $('#leftNav').find('.menu'), clicked = [];
  menu.find('li')
    .first().addClass('selectedAccordion navSectionHead')
      .end()
    .filter( function() {
      var $this = $(this),
          ul = $this.children('ul');
      if (ul.length == 1) {
        var el = $this.clone();
        el.children('ul').remove().end().prependTo(ul);
        return true;
      }
    }).addClass('selectedArrow')
    .children('a')
      .click(function(){
        $(this).parent()
          .toggleClass('trikiti').children('ul').slideToggle(200)
        return false;
      })
    /* popstate */
    .end().end()
    .find('a')
    .filter(function() {
      return $(this).siblings('ul').length === 0 
    })
    .click( getpage );

    $('.banner').click( getpage );

    /* handle back navigation */
  var popped = ('state' in window.history), initialURL = location.href
  $(window).bind('popstate', function() {
      var initialPop = !popped && location.href == initialURL
      popped = true
      if ( initialPop ) return
    
      $.get(location.pathname, function(data){
        $data = $(data);
        $('#primary').fadeOut(200, function() {
          $(this).html($data.find('#primary'))
          .fadeIn(200);
        });
      });
  });
   

/* --------- Search box clear --------- */
	$(".wTextInput").focus(function () {
		if ($(this).val() === $(this).attr("title")) {
			$(this).val("");
		}
	}).blur(function () {
		if ($(this).val() === "") {
			$(this).val($(this).attr("title"));
		}
	});

/* ---- Weather widget ----- */
  $('#weather').weather();


});
