<div class="row">
   <div class="col-xs-12">
      <h1>Account</h1>
   </div>
</div>
<hr>
<form method="post" id="account_form" class="row">
   <div class="col-xs-12">
   
      <?php foreach ($this->fields as $key => $field) { ?>
      <?php $label = \leslie::translate(str_replace(array('_', '-'), ' ', $field['name'])) ?>
      <fieldset class="form-group">
         <input type="<?php echo $field['type'] ?>" name="items[items_fields][<?php echo $key ?>][value]" <?php if (!empty($this->item['fields'][$field['id']])) { echo 'value="' . $this->item['fields'][$field['id']] . '"'; } ?> id="register-form-<?php echo $field['name'] ?>" placeholder="<?php echo $label ?>" class="form-control"<?php if ($field['required']) { echo ' required'; } ?>>
         <input type="hidden" name="items[items_fields][<?php echo $key ?>][id_type]" value="<?php echo $field['id'] ?>">
      </fieldset>
      <?php } ?>

      <fieldset class="form-group">
         <div class="input-group">
         <input type="email" value="<?php echo $this->user['email'] ?>" readonly class="form-control" aria-describedby="basic-addon2">
         <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/account/email" class="input-group-addon" id="basic-addon2"><?php echo leslie::translate('update') ?></a>
         </div>
      </fieldset>
      <fieldset class="form-group text-right">
      <a href="password-update"><?php echo leslie::translate('update') ?> password</a>
      </fieldset>
      <fieldset class="btn-toolbar">
         <button type="submit" name="action" value="update" class="btn btn-primary pull-right"><strong><?php echo leslie::translate('update') ?></strong> <?php echo strftime('%d %b %Y %H:%M', strtotime($this->user['update'])) ?></button>
         <a class="btn btn-danger" href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/account/out"><i class="glyphicon glyphicon-off" aria-hidden="true"></i>  <?php echo ucfirst(leslie::translate('exit')) ?></a>
         
      </fieldset>
   </div>
</form>

<script>
   $(function () {
      $('#account_form').validate({
         rules: {
           'users[email]': {
             required: true,
             email: true
           }
         }
      });
   });
</script>