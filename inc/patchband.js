jQuery(document).ready(function($) {
  var canvas = $('.patch-band-canvas'),
      duration = 200,
      colors = {
        gold   : '#D7A900',
        tan    : '#F3F2E9',
        purple : '#39275B'
      };

  $('body').css('background','none');
  canvas.on('click', 'a', function() { return false; })

  $('.header-show').change(function() {
    var distance = (this.checked) ? 125 : 0;
    $('.wordmark').animate({left:distance}, duration);
    $('.patch').fadeToggle(duration, function(){ 
      $('header').toggleClass('hide-patch')
    });
  });

  $('input[name="patchband[patch][header][color]"]').change(function(){
    $('.patch').animate({
      backgroundColor:colors[this.value]
    }, duration, function() {
      $('header').toggleClass('purple-patch');
    });
  });

  $('input[name="patchband[band][header][color]"]').change(function(){
    $('#thin-strip').animate({
      backgroundColor:colors[this.value]
    }, duration, function() {
      $('header').toggleClass('tan-band')
    });
  });

  $('input[name="patchband[wordmark][header][color]"]').change(function(){
    $('header').toggleClass('wordmark-white');
  })


//  if( $('#custom-wordmark').length > 0 ) 
//    $('.wordmark').css('background', 'url(' + $('#custom-wordmark').data('url') + ') no-repeat transparent').width(400).height(75);

});
