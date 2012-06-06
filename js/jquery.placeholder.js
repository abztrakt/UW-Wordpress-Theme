!function ($) {

  "use strict"; // jshint ;_;

  /* PLACEHOLDER CLASS
   * =====================*/

  var inputs = '[placeholder]'
    , supported 
    , placeholder
    , Placeholder = function() {}

  Placeholder.prototype = {

    constructor: Placeholder
    
  , toggle: function(e) {
      var $this = $(this)

      supported = isCompatible();

      if (typeof placeholder == 'undefined') 
        placeholder = $this.attr('placeholder')

      if (supported) return false

      $this.val( $this.val() == '' ? placeholder : $this.val())

      if (e.type == 'focusin')
        $this.val( $this.val() == placeholder ? '' : $this.val() )
  
       return false
    }
  }

  function isCompatible() {
      if (typeof supported !== 'undefined') 
        return supported

      var input = document.createElement('input');
      return supported = ('placeholder' in input);
  }

  $.fn.placeholder = function (option) {
    return this.each(function() {
      var $this = $(this)
        , data = $this.data('placeholder')
      if (!data) $this.data('placeholder', (data = new Placeholder(this)))
      if (typeof option == 'string') data[option].call($this)
    
    })
  }

  $.fn.placeholder.Constructor = Placeholder

  $(function() {
    if ( !isCompatible() ) 
      $(inputs).each(function(){ $(this).val($(this).attr('placeholder')) })

    $('body')
      .on('focus.placeholder blur.placeholder', inputs, Placeholder.prototype.toggle)
  })


}(window.jQuery);
