/* Tiles */

$(document).ready(function() {
  var ios4 = navigator.userAgent.match(/OS 4_\d like Mac OS X/i);
  var canvas = $('#topStories');
  var content = $('#content');
  var stories = canvas.find('.storyContainer');
  var width = (document.documentElement.clientWidth > 500 ? stories.first().width() : document.documentElement.clientWidth);
  var start = startY = deltaX = time = scroll = moved = 0;
  var placement = Math.ceil(stories.length/2) - 1;
  var pos = -1 * placement * width;
  var end = (stories.length - 1) * 320;

  var arrowLeft = $('#arrowLeft');
  var arrowRight = $('#arrowRight');

  var touched;
  var next;
  var prev;
  var classname;

  canvas.css('webkitTransform', 'translate3d('+pos+'px,0,0)'); // center the tiles

  stories.bind('touchstart touchmove touchend', function(e) {
    var $this = $(this);
    switch(e.type) {
      case 'touchstart':
        start = e.originalEvent.touches[0].pageX;
        startY = e.originalEvent.touches[0].pageY;
        time = Number(new Date());
        scroll = 0;
        moved = 0;
        touched = $(e.target);
        if (touched.hasClass('tileAnchorHeadline')){
          touched = touched.parent();
        }
        classname = touched.attr('class').split(' ')[0];

        next = $this.next().find('.'+classname);
        prev = $this.prev().find('.'+classname);

        canvas.css('webkitTransitionDuration', '0ms');
        arrowLeft.css('webkitTransitionDuration', '0ms');
        arrowRight.css('webkitTransitionDuration', '0ms');

        touched.css('webkitTransitionDuration', '0ms');
        $this.css('webkitTransitionDuration', '0ms');
        next.css('webkitTransitionDuration', '0ms');
        prev.css('webkitTransitionDuration','0ms');
        nav.css('webkitTransitionDuration','0ms');
        break;
      
      case 'touchmove':
        deltaX = e.originalEvent.touches[0].pageX - start;
        //deltaX = e.originalEvent.pageX - start;
        scroll = (scroll === 0 && Math.abs(deltaX) < Math.abs(e.originalEvent.touches[0].pageY - startY) ? 2 : (scroll !== 2 ? 1 : 0));
        //scroll = (scroll === 0 && Math.abs(deltaX) < Math.abs(e.originalEvent.pageY - startY) ? 2 : (scroll !== 2 ? 1 : 0));
        moved = 1;
        if(scroll === 1) {
          e.preventDefault();
          canvas.css('webkitTransform',  "translate3d(" + (deltaX + pos) + "px,0,0)");
          arrowLeft.css('webkitTransform',  "translate3d(" + -.5 * Math.abs(deltaX) + "px,0,0)");
          arrowRight.css('webkitTransform',  "translate3d(" + .5 * Math.abs(deltaX) + "px,0,0)");
          $this.css('webkitTransform',  "translate3d(" + .5 * (deltaX) + "px,0,0)");
          touched.css('webkitTransform',  "translate3d(" + .4 * (deltaX) + "px,0,0)");
          next.css('webkitTransform',"translate3d(" + -0.3 * deltaX + "px,0,0)");
          prev.css('webkitTransform',"translate3d(" + -0.3 * deltaX + "px,0,0)");
          nav.css('webkitTransform',"translate3d(" + -0.3 * deltaX + "px,0,0)");
        }
        break;

      case 'touchend':
        //TODO: better solution?
        if( e.target.href != undefined && !moved ){
          window.location.href = e.target.href;
        } else {
          pos += ((Number(new Date()) - time < 250 && Math.abs(deltaX) > 20 || Math.abs(deltaX) > width / 2) && scroll !== 2 ?
                 (deltaX + pos > 0 || Math.abs(pos) === end && end + deltaX < end ? 0 :
                 (deltaX > 0 ? width : -width)) : 0);
        }
        placement = -Math.ceil(pos / width);
        nav.children().removeClass().eq(placement).addClass('active');
        content.removeClass();
        if (placement == 0) { 
          content.addClass('firstTile');
        }
        if (placement == stories.length - 1) {
          content.addClass('lastTile');
        }

        canvas.css('webkitTransitionDuration', '300ms');
        canvas.css('webkitTransform', "translate3d(" + pos + "px,0,0)");
        arrowLeft.css('webkitTransitionDuration', '500ms');
        arrowLeft.css('webkitTransform', "translate3d(0,0,0)");
        arrowRight.css('webkitTransitionDuration', '500ms');
        arrowRight.css('webkitTransform', "translate3d(0,0,0)");

        touched.css('webkitTransitionDuration', '500ms');
        touched.css('webkitTransform', "translate3d(0,0,0)");
        $this.css('webkitTransitionDuration', '500ms');
        $this.css('webkitTransform', "translate3d(0,0,0)");
        next.css('webkitTransitionDuration', '700ms');
        next.css('webkitTransform', "translate3d(0,0,0)");
        prev.css('webkitTransitionDuration', '700ms');
        prev.css('webkitTransform', "translate3d(0,0,0)");

        nav.css('webkitTransitionDuration', '1200ms');
        nav.css('webkitTransform', "translate3d(0,0,0)");
        break;

      default:
        return false;
        break;
    }
    
  });

  //[TODO] come up with transition animation and clean up
  arrowLeft.click( function(e) {
    e.preventDefault();
    if( placement <= 0 ) return;
    pos += width;
    placement = -Math.ceil(pos / width);
    nav.children().removeClass().eq(placement).addClass('active');
    content.removeClass();
    if (placement == 0) { content.addClass('firstTile'); }
    if (placement == stories.length - 1) { content.addClass('lastTile'); }
    canvas.css('webkitTransitionDuration', '300ms');
    canvas.css('webkitTransform', "translate3d(" + pos + "px,0,0)");

    return false;
  });

  arrowRight.click(function(e) {
    e.preventDefault();
    if( placement >= stories.length - 1 ) return;
    pos -= width;
    placement = -Math.ceil(pos / width);
    nav.children().removeClass().eq(placement).addClass('active');
    content.removeClass();
    if (placement == 0) { content.addClass('firstTile'); }
    if (placement == stories.length - 1) { content.addClass('lastTile'); }
    canvas.css('webkitTransitionDuration', '300ms');
    canvas.css('webkitTransform', "translate3d(" + pos + "px,0,0)");
  
    return false;
  });



/* dots */
  var nav = canvas.after('<div id="firenze-nav"><ul></ul></div>').next().children('ul');
  $.each(stories, function(i){
    var active = (i == placement) ? 'class="active"' : '';
    nav.append('<li id="dot-'+i+'" '+active+'/>');
  });

});
