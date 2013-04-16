jQuery(document).ready(function($){

  var $excerpt = $('#excerpt')

  // Force checks the no-confirmation necessary when registering users
  $('input[name=noconfirmation]').closest('tr').hide().end().attr('checked', true);


  // For media, if the alt is left blank use the title as the default value
  $('body').on('click', '#save', function() {
    var $alt = $('tr.image_alt')
      , $title = $('tr.post_title')

    if ( $alt.length == 1 && $alt.find('input').val() == '' ) 
    {
      $alt.find('input').val($title.find('input').val())
    }
      
  });


  if ( !$excerpt.length )
    return;

  $excerpt
      .bind('keyup', function() {
        var $this = $(this)
          , $count = $('#excerpt-word-count')
          , wordc  = $this.val().split(' ').length - 1
          , limit  = 55
          , str    = wordc == limit-1 || wordc == limit+1 ? ' word left)' : ' words left)'

        if ( wordc > limit ) 
          str = str.replace('left', 'over')
        
        $count.html( '(' + Math.abs(limit - wordc) + str )

      })
      .closest('#postexcerpt').find('h3')
        .append(' <small id="excerpt-word-count"></small>')
        .end().end()
        .trigger('keyup')

})
