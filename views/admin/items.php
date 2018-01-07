<div class="page-header">
   <h1><?php echo $this->title ?></h1>
</div>
<form method="post" id="items_form">
   <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
      <div class="btn-group" role="group" aria-label="First group">
         <a href="item?type=<?php echo $this->item_type['id'] ?>" class="btn btn-primary" title="<?php echo leslie::translate('new') ?>"><i class="glyphicon glyphicon-plus"></i></a>
         <a href="uploads?type=<?php echo $this->item_type['id'] ?>" class="btn btn-success" title="<?php echo leslie::translate('new') ?>">Upload multiplo</a>
         <button type="submit" name="action" value="delete" class="btn btn-danger" title="<?php echo leslie::translate('delete') ?>"><i class="glyphicon glyphicon-remove"></i></button>
      </div>
   </div>
   <hr>
   <table class="table table-striped items_table" id="items_table">
      <colgroup>
         <col>
         <col>
         <col style="text-align: center">
         <col>
      </colgroup>
      <thead>
         <tr>
            <th></th>
            <th>
               <input type="checkbox" id="check_all">
            </th>
            <th class="text-left"><?php echo leslie::translate('name') ?></th>
            <th class="text-left">URL</th>
            <th class="text-center"><?php echo leslie::translate('languages') ?></th>
            <th></th>
         </tr>

      </thead>
      <tbody>
      <?php foreach ($this->items as $item) { ?>
         <tr>
            <td>
               <i class="glyphicon glyphicon-sort items_sort_handle"></i>
            </td>
            <td>
               <input type="checkbox" name="items[items_list][id][]" value="<?php echo $item['id'] ?>" id="item_check_<?php echo $item['id'] ?>" class="item_select">
            </td>
            <td class="text-left">
               <?php if ($this->item['documents']) { ?>
               <img src="<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/' . $this->item_type['plural'] . '/small/' . $item['name'] ?>" class="items_image" onclick="javascript: window.open('<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/' . $this->item_type['plural'] . '/large/' . $item['name'] ?>', '<?php echo $item['name'] ?>', 'fullscreen=yes')"></td>
               <?php } else { ?>
               <?php echo htmlentities($item['name']) ?>
               <?php } ?>
            </td>
            <td class="text-left">
               <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/<?php echo \leslie::translate('contents') ?>/<?php echo $item['permalink'] ?>" target="_blank" title="<?php echo leslie::translate('view') ?>"><?php echo $item['permalink'] ?></a>
            </td>
            <td class="text-center">
               <?php foreach ($this->languages as $lang) { ?>
               <?php if (!empty($this->item_languages[$item['id']])) { ?>
               <?php if (in_array($lang['sign'], $this->item_languages[$item['id']])) { ?>
               <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/item?id=<?php echo $item['id'] ?>&lang=<?php echo $lang['id'] ?>" class="item_languages" title="<?php echo leslie::translate('Update') ?>">
                  <img src="<?php echo FRAMEWORK_URL_IMG . '/languages/' . $lang['sign'] ?>.png">
               </a>&nbsp;
               <?php } else { ?>
               <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/item?id=<?php echo $item['id'] ?>&lang=<?php echo $lang['id'] ?>" class="item_languages" title="<?php echo leslie::translate('Insert') ?>">
                  <img src="<?php echo FRAMEWORK_URL_IMG . '/languages/' . $lang['sign'] ?>.png">
               </a>&nbsp;
               <?php } ?>
               <?php } else { ?>
               <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/item?id=<?php echo $item['id'] ?>" class="btn btn-xs btn-default" title="<?php echo leslie::translate('Update') ?>">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
               <?php } ?>
               <?php } ?>
            </td>
            <td>
               <a href="javascript:void(0)" onclick="delete_item(<?php echo $item['id'] ?>)" class="btn btn-xs btn-danger" title="<?php echo leslie::translate('delete') ?>"><i class="glyphicon glyphicon-remove"></i></a>
            </td>
         </tr>
      <?php } ?>
      </tbody>
   </table>
</form>

<script>
   $(function () {
      $('#items_table').DataTable({
         language: {
             url: '<?php echo FRAMEWORK_URL_PLUG ?>/datatables/languages/italian.json'
         },
         columnDefs: [{
            targets: [0, 1, 3, 4, 5],
            searchable: false,
            orderable: false
         }]
      });
   });
</script>