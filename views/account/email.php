<div class="row">
   <div class="col-xs-12">
      <h1><?php echo leslie::translate('Update') ?> email</h1>
   </div>
</div>
<hr>
<form method="post" id="email_form" class="row">
   <div class="col-xs-12">

      <fieldset class="form-group">
         <input type="email" name="items[users][email]" class="form-control" required>
      </fieldset>
      
      <fieldset class="btn-toolbar">
         <button type="submit" name="action" value="update" class="btn btn-primary pull-right"><?php echo leslie::translate('update') ?></button>
         
      </fieldset>
   </div>
</form>

<script>
   $(function () {
      $('#email_form').validate({
         rules: {
           'users[email]': {
             required: true,
             email: true
           }
         }
      });
   });
</script>
