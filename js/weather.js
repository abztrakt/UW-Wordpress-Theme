//!function($) {
//
//  "use strict"; //hshint ;_;
//
//  var weather = '.weather'
//    , strip   = '#thin-strip'
//    , Weather = function (element) {
//        console.log('here');
//      
//      }
//
//}(window.jQuery)

jQuery(document).ready(function($) {

  var data = {
    q:'http://www.atmos.washington.edu/rss/home.rss',
    v:'2.0'
  }
  $.ajax({
    url: 'https://ajax.googleapis.com/ajax/services/feed/load?callback=?',
    dataType: 'jsonp',
    data: data,
    success: function(json) { 
      var icon = $.trim(json.responseData.feed.entries[2].title.split('|')[1]);
      var temp = $.trim(json.responseData.feed.entries[0].title.split('|')[1]);
      var html = '<li><a href="http://www.atmos.washington.edu/weather/forecast/">';
      html += '<img src="/concept/wp-content/themes/uw/img/weather/top-nav/'+icon+'.png" />';
      html += '</a></li>';
      html += '<li><a href="http://www.atmos.washington.edu/weather/forecast/">';
      html += 'Seattle '+temp;
      html += '</a></li>';
      $('#thin-strip').find('ul').append(html)
    }
  });
  

});
