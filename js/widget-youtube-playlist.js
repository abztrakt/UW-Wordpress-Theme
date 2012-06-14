/*!
 * Tiny Scrollbar 1.67
 * http://www.baijs.nl/tinyscrollbar/
 *
 * Copyright 2012, Maarten Baijs
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/gpl-2.0.php
 *
 * Date: 11 / 05 / 2012
 * Depends on library: jQuery
 *
 */
(function(a){function b(b,c){function q(){d.update();s();return d}function r(){j.obj.css(l,n/h.ratio);g.obj.css(l,-n);p["start"]=j.obj.offset()[l];var a=m.toLowerCase();h.obj.css(a,i[c.axis]);i.obj.css(a,i[c.axis]);j.obj.css(a,j[c.axis])}function s(){j.obj.bind("mousedown",t);j.obj[0].ontouchstart=function(a){a.preventDefault();j.obj.unbind("mousedown");t(a.touches[0]);return false};i.obj.bind("mouseup",w);if(c.scroll&&this.addEventListener){e[0].addEventListener("DOMMouseScroll",u,false);e[0].addEventListener("mousewheel",u,false)}else if(c.scroll){e[0].onmousewheel=u}}function t(b){p.start=k?b.pageX:b.pageY;var c=parseInt(j.obj.css(l));o.start=c=="auto"?0:c;a(document).bind("mousemove",w);document.ontouchmove=function(b){a(document).unbind("mousemove");w(b.touches[0])};a(document).bind("mouseup",v);j.obj.bind("mouseup",v);j.obj[0].ontouchend=document.ontouchend=function(b){a(document).unbind("mouseup");j.obj.unbind("mouseup");v(b.touches[0])};return false}function u(b){if(!(g.ratio>=1)){var b=b||window.event;var d=b.wheelDelta?b.wheelDelta/120:-b.detail/3;n-=d*c.wheel;n=Math.min(g[c.axis]-f[c.axis],Math.max(0,n));j.obj.css(l,n/h.ratio);g.obj.css(l,-n);if(c.lockscroll||n!==g[c.axis]-f[c.axis]&&n!==0){b=a.event.fix(b);b.preventDefault()}}}function v(b){a(document).unbind("mousemove",w);a(document).unbind("mouseup",v);j.obj.unbind("mouseup",v);document.ontouchmove=j.obj[0].ontouchend=document.ontouchend=null;return false}function w(a){if(!(g.ratio>=1)){o.now=Math.min(i[c.axis]-j[c.axis],Math.max(0,o.start+((k?a.pageX:a.pageY)-p.start)));n=o.now*h.ratio;g.obj.css(l,-n);j.obj.css(l,o.now)}return false}var d=this;var e=b;var f={obj:a(".viewport",b)};var g={obj:a(".overview",b)};var h={obj:a(".scrollbar",b)};var i={obj:a(".track",h.obj)};var j={obj:a(".thumb",h.obj)};var k=c.axis=="x",l=k?"left":"top",m=k?"Width":"Height";var n,o={start:0,now:0},p={};this.update=function(a){f[c.axis]=f.obj[0]["offset"+m];g[c.axis]=g.obj[0]["scroll"+m];g.ratio=f[c.axis]/g[c.axis];h.obj.toggleClass("disable",g.ratio>=1);i[c.axis]=c.size=="auto"?f[c.axis]:c.size;j[c.axis]=Math.min(i[c.axis],Math.max(0,c.sizethumb=="auto"?i[c.axis]*g.ratio:c.sizethumb));h.ratio=c.sizethumb=="auto"?g[c.axis]/i[c.axis]:(g[c.axis]-f[c.axis])/(i[c.axis]-j[c.axis]);n=a=="relative"&&g.ratio<=1?Math.min(g[c.axis]-f[c.axis],Math.max(0,n)):0;n=a=="bottom"&&g.ratio<=1?g[c.axis]-f[c.axis]:isNaN(parseInt(a))?n:parseInt(a);r()};return q()}a.tiny=a.tiny||{};a.tiny.scrollbar={options:{axis:"y",wheel:40,scroll:true,lockscroll:true,size:"auto",sizethumb:"auto"}};a.fn.tinyscrollbar=function(c){var c=a.extend({},a.tiny.scrollbar.options,c);this.each(function(){a(this).data("tsb",new b(a(this),c))});return this};a.fn.tinyscrollbar_update=function(b){return a(this).data("tsb").update(b)};})(jQuery)

$(window).load(function() {
  if ( $('#youtubeapi').length < 1) return;
  if (swfobject.getFlashPlayerVersion().major < 11) {
      var html = '<div class="alert alert-error">'+
                    '<strong>You need to upgrade your Adoble Flash Player to watch the UW Today videos.</strong><br/>'+
                    '<a href="http://get.adobe.com/flashplayer/" title="Flash player upgrade">Download it here from Adobe.</a>'+
                  '</div>';
    $('#nc-video-player').html(html);
    return;
  }
    
  var $wrapper = $('#tube-wrapper');
  var $vidSmall = $('#vidSmall');
  var $vidContent = $('#vidContent');
  var params = { allowScriptAccess: "always", wmode:'transparent' };
  var atts = { id: "customplayer" };
  var proxy = '//ajax.googleapis.com/ajax/services/feed/load?';
  var playlist = $('#youtubeapi').data('pid');

  var gets = jQuery.param({
      enablejsapi:1,
      playerapiid:'uwplayer',
      version:3,
      controls:1,
      autoplay:0,
      rel:0,
      modestbranding:1,
      theme:'light'
  });

  $vidSmall.tinyscrollbar();

  $.getJSON('//gdata.youtube.com/feeds/api/playlists/'+playlist+'/?callback=?',{'alt':'json','v':'2'}, function (data){
    var video = data.feed.entry[0].media$group.yt$videoid.$t;
    var count = data.feed.entry.length;
    swfobject.embedSWF("//www.youtube.com/v/"+video+"?"+gets,
                       "youtubeapi", "425", "356", "8", null, null, params, atts);
    $.each(data.feed.entry, function(index,video) {
        var img = video.media$group.media$thumbnail[0],
            video_id  =  video.media$group.yt$videoid.$t,
            title = video.title.$t,
            dur = video.media$group.yt$duration.seconds;

        var html = '<a id="'+ video_id +'" class="video" href="#">'+
              '<img class="playBtn" src="/cms/wp-content/themes/news/img/play.png" />'+
                    '<img src="'+img.url.replace(/https?:\/\//, '//')+'" width="'+img.width+'" height="'+img.height+'" />'+
                    '<span class="title">'+title+'</span>'+
                    '<span class="duration">'+Math.floor(dur/60)+':'+(dur % 60)+'</span>'+
                   '</a>';

        $vidContent.append(html);
        $vidSmall.tinyscrollbar_update();
        if (--count==0) $vidSmall.find('.scrollbar').show();
      });
  });

  $wrapper.delegate('a.video', 'click', function(e) {
      e.preventDefault();
      play(this.id);
      return false;
  });

  $vidSmall.one('mouseenter', function() {
    $(this).tinyscrollbar_update();
  })
});
function onYouTubePlayerReady(playerid){
  uwplayer = document.getElementById("customplayer");
}
function play(id){
  uwplayer.loadVideoById(id);
};
