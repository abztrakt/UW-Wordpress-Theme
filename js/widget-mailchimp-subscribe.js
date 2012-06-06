$(document).ready(function(){

   var form = $('#mailchimp'),
       response = form.find('.response'),
       submit = form.find('input[type=submit]');
   var msg  = {
     error   : 'An error has occurred. Please try again later.',
     failure : "Server's down! Please try again later."
   };
   form.submit(function() {
      submit.attr('disabled',true);
      response.removeClass().html('Adding email address...');
      $.ajax({
        url: 'http://www.washington.edu/news/mailchimp/mailchimp.php',
        type: 'POST',
        data: { bananas: true,
                email  : escape($('#email').val()),
                group   : form.find('input[name=pref]:checked').val() 
        },
        success: function(res) {
          if( res.indexOf('already subscribed') > -1 ) {
            response.addClass('info').html(res);
          } else if( res.indexOf('Error') > -1 ) {
            response.addClass('error').html(msg.error)
          } else {
            response.addClass('success').html(res);
          }
          submit.attr('disabled', false);
        },
        error: function(err) {
          response.html(msg.failure);
        }
      });
      return false;
  });

});
