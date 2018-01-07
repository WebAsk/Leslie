<form action="<?php echo $this->url ?>" id="dropzone" class="dropzone"></form>

<div class="table-responsive">

   <table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
         <tr>
            <th>Name</th>
            <th></th>
         </tr>
      </thead>
      <tfoot>
         <tr>
            <th>Name</th>
            <th></th>
         </tr>
      </tfoot>
      <tbody>

         <?php foreach ($this->items as $item) { ?>
         <tr>
            <td><a href="javascript: void(0)" onclick="javascript: window.open('<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/' . $this->document_type['plural'] . '/' . $item['name'] ?>', '<?php echo $item['name'] ?>', 'fullscreen=yes')"><?php echo htmlentities($item['title']) ?></a></td>
            <td class="text-center">
               <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/account/delete/<?php echo $item['code'] ?>?location=<?php echo $this->url ?>" class="remove" title="Rimuovi questo documento"><i class="icon-trash2"></i></a>
            </td>
         </tr>
         <?php } ?>

      </tbody>
   </table>

</div>

<script>

   $(document).ready(function() {
      $('#datatable1').DataTable({
         language: {
             url: '<?php echo FRAMEWORK_URL_PLUG ?>/datatables/languages/italian.json'
         }
      });
      $("#dropzone").dropzone({ 
         url: "<?php echo $this->url ?>",
         queuecomplete: function () {
            window.location.href = '<?php echo $this->url ?>';
         }
      });
   });

</script>