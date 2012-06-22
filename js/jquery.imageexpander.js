$(document).ready(function() {

  var imgs  = '.entry-content img'
    , spans = '.image-magnifier'

  $('body')
      .on('click.imageexpander', imgs, function() {
      
          var $this = $(this)
            , cached = $this.siblings('img').length > 0 
            , $anchor = $this.parent('a')
            , $caption = $this.closest('.wp-caption')
            , cap

        if ($this.hasClass('royalImage')) 
          return;

          if( !cached ) {
              var img = new Image()
              img.className = 'image-expanded ' // + $this.attr('class')
              img.style.display = 'none'
              $this.after(img)
              $anchor.data('owidth', $this.width())
              $caption.data('opadding', $caption.css('padding'))
          }

          size    = $this.hasClass('image-expanded') ? $anchor.data('owidth') : 600
          cap     = $this.hasClass('image-expanded') ? 0 : 10 ;
          padding = $this.hasClass('image-expanded') ? $caption.data('opadding') : '5px 0px 15px 0px'

          $anchor.data('expanded', 'true')

          $this
            .removeAttr('height')
            .stop()
            .animate({
              'width': size
            }, {
              duration:200,
              queue: false,
              easing:'swing',
              step : function(step, fx) {
                // caption adds 10px 
                $caption.width(step + cap) 
                if ( size === 600 && step > 550 )
                  $caption.css('padding',padding).width(step)
                else if ( size !== 600 && step < 550 ) {
                  $caption.css('padding',padding)
                  cap = 10
                }
              },
              complete: function() {
                if ( !cached ) {
                  img.src = $anchor.attr('href')
                  img.onload =  function() {
                    $(this).siblings('img').andSelf().css('width',size).toggle()
                      .end().siblings().not('img').toggle();
                  }
                } else {
                  $(this).siblings('img').andSelf().css('width',size).toggle()
                    .end().siblings().not('img').toggle();
                }
              }
          })
          return false;
      })

    $(imgs).each(function() { 
      var $this = $(this)
        , hasCaption = $this.closest('.wp-caption').length > 0
        , $a

      // patch for images without captions
      if ( !hasCaption )
        $this.parent('a').addClass($this.attr('class') + ' wp-caption').width($this.width())
        
      $a = $this.parent('a').clone()
      $a.html('High resolution')
        .attr('target','_blank')
        .addClass('image-fullsize')
        .hide()

      $this.parent('a')
        .append($a)
        .append('<span class="image-magnifier">Click to expand</span>')
    })


});
