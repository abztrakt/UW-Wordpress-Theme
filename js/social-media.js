$(document).ready(function() {


  var social_events = function() {
    var $this = $(this);
    $this.addClass('loaded');
    var facebook_button = $this.find('.facebook-like').removeClass().addClass('fb-like').unwrap('a');
    var twitter_button = $this.find('.twitter-share').removeClass().addClass('twitter-share-button');
    $this.find('.count').hide();

    twttr.widgets.load();
    //tweet_button.render();
    FB.XFBML.parse();
    $this.unbind('mouseenter');
  }

  $('.social-media').bind('mouseenter', social_events )

});
