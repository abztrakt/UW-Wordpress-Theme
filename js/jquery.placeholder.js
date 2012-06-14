$(document).ready(function() {
    var input     = document.createElement('input'),
        supported = ('placeholder' in input),
        inputs    = '[placeholder]';

    if ( !supported ) {

      $(inputs).each(function(){ $(this).val($(this).attr('placeholder')) })
      
      $('body').on('focus.placeholder blur.placeholder', inputs, function(e) {
        var $this = $(e.target)
          , placeholder = $this.attr('placeholder');

        $this.val( $this.val() == '' ? placeholder : $this.val());

        if (e.type == 'focusin')
          $this.val( $this.val() == placeholder ? '' : $this.val() )
    
         return false
        
      });
    
    }
})
