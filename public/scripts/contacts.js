$(function() {
   $('#contact-form-ajax').submit( function( event ){
      event.preventDefault();
      var form = $(this);
      form.append('<div id="response"></div>');
      var form_response = $('#response');
      form_response.append('<img src="' + FRAMEWORK_URL + '/images/loaders/loading1.gif">');
      $.ajax({
         url: form.attr('action'),
         method: form.attr('method'),
         data: form.serialize(),
         dataType: 'json'
      }).done( function( data ){
         if (data.response == true) {
            form_response.css('color','green').text(data.message);
         } else {
            form_response.css('color','red').text(data.message);
         }
      });
   });
   
});


