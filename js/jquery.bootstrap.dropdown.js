/* ============================================================
 * bootstrap-dropdown.js v2.0.3.1
 * http://twitter.github.com/bootstrap/javascript.html#dropdowns
 * ============================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */


!function ($) {

  "use strict"; // jshint ;_;


 /* DROPDOWN CLASS DEFINITION
  * ========================= */

  var toggle  = '[data-toggle="dropdown"]'
    , header  = '#branding'
    , caret   = '.navbar-caret'
    , open    = '.open'
    , timeout
    , Dropdown = function (element) {
        var $el = $(element).on('click.dropdown.data-api', this.toggle)
        $('html').on('click.dropdown.data-api', function () {
          $el.parent().removeClass('open');
        })
      }

  Dropdown.prototype = {

    constructor: Dropdown

  , isActive: false
  , keys: {enter:13, esc:27, tab:9, left:37, up:38, right:39, down:40, spacebar:32 }

  , toggle: function (e) {
      var $this = $(e.target)
        , $header = $(header)
        , $parent
        , $caret
        , selector
        , isActive

      if ($this.is('.disabled, :disabled')) return

      if( $this.siblings('ul.dropdown-menu').length === 0 )
        return true;

      //selector = $this.attr('data-target')

      //if (!selector) {
      //  selector = $this.attr('href')
      //  selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
      //}

      $caret = $(caret)

      $parent = $(selector)
      $parent.length || ($parent = $this.parent())

      isActive = $parent.hasClass('open')

      if (!isActive) { 
        $parent.toggleClass('open')
        $(open).not($parent).removeClass('open')
        $('div.collapse').removeClass('collapse')
        if ( $('a.btn-navbar').is(':hidden') ) $header.height(350)
        $caret.show()
        Dropdown.prototype.isActive = true
      } 

      if (isActive && e.type == 'click')
        return true

      if (e.type === 'keydown') {
        $(e.target).parent().find('ul a').first().focus();
      }

      $caret.css('left', $parent.position().left + 20)

      return false
    }


  , timer: function(e) {
      var $this = $(this)

      if (timeout) {
        clearTimeout(timeout)
        timeout = null
      }

      if (e.type == 'mouseleave') { 
        timeout = setTimeout ( function() {
          clearMenus()
        }, 100)
        return false;
      }

      if (Dropdown.prototype.isActive) 
        $this.trigger('customclick.dropdown.data-api')

      timeout = setTimeout( function(){
        clearMenus()
        $this.trigger('click.dropdown.data-api')
      }, 200)
    }

  , handleKeys : function(e) {

      if (e.altKey || e.ctrlKey)
        return true;

      var keys = Dropdown.prototype.keys
        , $this = $(this)
        , $anchors = $('a.dropdown-toggle')


      switch(e.keyCode) {
        case keys.enter:
          Dropdown.prototype.toggle(e);
          return true;

        case keys.spacebar:
        case keys.up:
        case keys.down:
          var fake_event = jQuery.Event( 'keydown', { keyCode: keys.enter } );
          $this.trigger(fake_event);
          return false;

        case keys.esc:
          clearMenus();
          return false;

        case keys.tab:
          clearMenus();
          return true;
        
        case keys.left:
          var index = $anchors.index($this)
          $anchors.eq(index-1).focus()
          return false;

        case keys.right:
          var index = $anchors.index($this)
          //fix last anchor to circle focus back to first anchor
          index = index === $anchors.length-1 ? -1 : index; 
          $anchors.eq(index+1).focus()
          return false;

        default:
          return true;
      }
  
    }

  , handleDropdownKeys : function(e) {

      if (e.altKey || e.ctrlKey)
        return true;

      var keys     = Dropdown.prototype.keys
        , $this    = $(this)
        , $anchors = $this.closest('ul').find('a')

      
      switch(e.keyCode) {

        case keys.spacebar:
          document.location.href = $this.attr('href');
          return false;

        case keys.tab:
          clearMenus();
          return true;

        case keys.esc:
          $this.blur().closest('ul').siblings('a').focus();
          clearMenus();
          return true;

        case keys.down:
          var index = $anchors.index($this)
          //fix last anchor to circle focus back to first anchor
          index = index === $anchors.length-1 ? -1 : index; 
          $anchors.eq(index+1).focus();
          return false;

        case keys.up:
          var index = $anchors.index($this)
          $anchors.eq(index-1).focus();
          return false;

        case keys.left:
          $this.blur().closest('ul').siblings('a').focus();
          clearMenus();
          return false;

        case keys.right:
          $this.blur().closest('ul').parent().next('li').children('a').focus();
          clearMenus();
          return false;

        default:
          var chr = String.fromCharCode(e.which)
            , exists = false;
          $anchors.filter(function() {
            exists = this.innerHTML.charAt(0) === chr
            return exists;
          }).first().focus();
          return !exists;

      }
      
    }
  , backtrace: function(e) {
      clearTimeout(timeout)
      timeout = null
    }
  }

  function clearMenus(e) {
    if ( e && $(e.target).parent().hasClass('open') || 
         e && $(e.target).closest('.open').length > 0 ) return

    var $header = $(header)
    Dropdown.prototype.isActive = false
    $header.css('height','auto')
    $(toggle).parent().removeClass('open');
    if ( $('a.btn-navbar').is(':hidden') ) $header.height(165);
    $(caret).hide();
  }


  /* DROPDOWN PLUGIN DEFINITION
   * ========================== */

  $.fn.dropdown = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('dropdown')
      if (!data) $this.data('dropdown', (data = new Dropdown(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  $.fn.dropdown.Constructor = Dropdown


  /* APPLY TO STANDARD DROPDOWN ELEMENTS
   * =================================== */

  $(function () {
    $('html').on('click.dropdown.data-api', function(e) { clearMenus(e); })
    $('body')
      .on('click.dropdown', '.dropdown form', function (e) { e.stopPropagation() })
      .on('click.dropdown.data-api', toggle, Dropdown.prototype.toggle)
      .on('customclick.dropdown.data-api', toggle, Dropdown.prototype.toggle)
      .on('mouseenter.dropdown.data-api', toggle, Dropdown.prototype.timer)
      .on('mouseleave.dropdown.data-api', '#access', Dropdown.prototype.timer)
      .on('mouseenter.dropdown.data-api', '#access', Dropdown.prototype.backtrace)
      .on('keydown.dropdown.data-api', 'a.dropdown-toggle', Dropdown.prototype.handleKeys)
      .on('keydown.dropdown.data-api', 'ul.dropdown-menu a', Dropdown.prototype.handleDropdownKeys)

    var len = $(toggle).length - 1
    $(toggle).parent().filter(function(n,i) {
        return n > len / 2;
    }).addClass('rightside')

  })

}(window.jQuery);
