/* ============================================================
 * bootstrap-dropdown.js v2.0.3
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

  var toggle = '[data-toggle="dropdown"]'
    , header = '#branding'
    , caret  = '.navbar-caret'
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

  , toggle: function (e) {
      var $this = $(this)
        , $header = $(header)
        , $parent
        , $caret
        , selector
        , isActive

      if ($this.is('.disabled, :disabled')) return

      selector = $this.attr('data-target')

      if (!selector) {
        selector = $this.attr('href')
        selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
      }

      $caret = $(caret)

      $parent = $(selector)
      $parent.length || ($parent = $this.parent())

      isActive = $parent.hasClass('open')

      if (!isActive) { 
        $parent.toggleClass('open');
        $('div.collapse').removeClass('collapse');
        if ( $('a.btn-navbar').is(':hidden') ) $header.height(350);
        $caret.show()
        Dropdown.prototype.isActive = true
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
        $this.trigger('click.dropdown.data-api')

      timeout = setTimeout( function(){
        clearMenus()
        $this.trigger('click.dropdown.data-api')
      }, 200)
    }

  , backtrace: function(e) {
      clearTimeout(timeout)
      timeout = null
    }
  }

  function clearMenus() {
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
    $('html').on('click.dropdown.data-api', clearMenus)
    $('body')
      .on('click.dropdown', '.dropdown form', function (e) { e.stopPropagation() })
      .on('click.dropdown.data-api', toggle, Dropdown.prototype.toggle)
      .on('mouseenter.dropdown.data-api', toggle, Dropdown.prototype.timer)
      .on('mouseleave.dropdown.data-api', '#access', Dropdown.prototype.timer)
      .on('mouseenter.dropdown.data-api', '#access', Dropdown.prototype.backtrace)
    })

}(window.jQuery);
