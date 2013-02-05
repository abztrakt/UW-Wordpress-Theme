jQuery(document).ready(function($){
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

})
