jQuery(document).ready(function($) {
  var canvas = $('.patch-band-canvas');

  $('body').css('background','none');
  canvas.on('click', 'a', function() { return false; })

  $('.header-show').change(function() {
    $('.patch').fadeToggle();
  });

});
