<div class="page-header">
   <h1><?php echo $this->title ?></h1>
</div>
<form method="post" id="contents">
<button type="submit" name="action" value="delete" class="btn btn-danger" title="<?php echo leslie::translate('delete') ?>"><i class="glyphicon glyphicon-remove"></i></button>
<table class="table">
   <thead>
      <tr>
         <th></th>
         <th><?php echo leslie::translate('title') ?></th>
         <th><?php echo leslie::translate('language') ?></th>
         <th><?php echo leslie::translate('actions') ?></th>
      </tr>

   </thead>
   <tbody>
   <?php foreach ($this->items as $content) { ?>
      <tr>
         <td><input type="checkbox" name="items[items_list][id][]" value="<?php echo $content['id'] ?>"></td>
         <td><?php echo $content['title'] ?></td>
         <td><img src="<?php echo FRAMEWORK_URL_IMG . '/languages/' . $content['lang'] ?>.png" width="24" height="24"></td>
         <td><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/item?code=<?php echo $content['code'] ?>&lang=<?php echo $content['id_language'] ?>" class="btn btn-xs btn-primary" title="<?php echo leslie::translate('update') ?>"><i class="glyphicon glyphicon-pencil"></i></a> <a href="documents?type_id=2&content_id=<?php echo $content['id'] ?>" class="btn btn-xs btn-primary" title="<?php echo leslie::translate('documents') ?>"><i class="glyphicon glyphicon-picture"></i></a></td></tr>
   <?php } ?>
   </tbody>
</table>
</form>
<script>
   $(function () {
      $('#contents').submit(function() {
         return confirm("<?php echo leslie::translate('confirm deletion') ?>");
      });
   })
</script>
