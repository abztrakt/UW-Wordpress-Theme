$(window).load(function() {
  $('.communityphotos').waypoint(function() {
    var $this = $(this)
    if ( ! $this.is(':visible') ) return;
    $(this).find('img').each(function() {
      var $this = $(this);
      $this.attr('src', $this.data('src'));
    }).end().waypoint('destroy')
  },{
    offset: function() {
      // custom offset since we want the images loaded before they are scrolled to
      return $.waypoints('viewportHeight') + 0.5 * $(this).outerHeight() ;
    }
  })
});
