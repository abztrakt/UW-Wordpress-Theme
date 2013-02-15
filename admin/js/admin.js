jQuery(document).ready(function($){
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

  // // Autocomplete (combobox) for the author, page parent dropdowns 
  //if ( $().combobox ) 
  //{
  //  //$('#post_author_override').combobox()
  //  $('#post_author_override').width('100%').chosen()
  //  //$('#parent_id option').each(function() {
  //  //  console.log($(this).html().replace(/(&nbsp;)/g,"")) 
  //  //})
  //  //$('#parent_id').chosen()
  //}
})
