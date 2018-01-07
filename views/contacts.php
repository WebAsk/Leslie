<div class="page-header">
   <h1><?php echo $this->title ?></h1>
</div>
<form method="post" role="form" id="contact-form">
   <fieldset class="form-group">
      <input type="email" name="email" value="<?php echo $this->email ?>" required placeholder="Email" class="form-control">
   </fieldset>
   <fieldset class="form-group">
      <textarea name="message" rows="5" class="form-control" placeholder="<?php echo ucfirst(leslie::translate('message')) ?>" required><?php echo $this->message ?></textarea>
   </fieldset>
   <fieldset class="form-group">
      <div id="captcha"></div>
   </fieldset>

</form>
<script type="text/javascript">
   var captcha = function() {
      grecaptcha.render('captcha', {
         'sitekey' : '<?php echo $GLOBALS['PROJECT']['CAPTCHA']['KEY']['client']; ?>',
         'callback' : function(response) {
            if (response === $('#g-recaptcha-response').val()) {
               $('#contact-form').validate({
                  lang: 'it'
               });
               if ($('#contact-form').valid()) {
                  $('<input>').attr({type: 'hidden', name: 'action', value: 'send_email'}).appendTo('#contact-form');
                  $('#contact-form').submit();
               } else {
                  grecaptcha.reset();   
               }
            }
         }
     });
   };
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=captcha&render=explicit" async defer></script>

