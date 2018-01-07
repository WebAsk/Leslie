
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_content">
      <table id="datatable" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Codice</th>
            <th>Nome</th>
            <th>IP</th>
            <th>Browser</th>
            <th>Primo accesso</th>
            <th>Ultimo accesso</th>
            <th class="text-center">Attiva</th>
            <th></th>
          </tr>
        </thead>


        <tbody>
          <?php foreach ($this->items as $user) { ?>
          <tr>
             <td><?php echo $user['id'] ?></td>
             <td><?php echo $user['code'] ?></td>
             <td><?php echo $user['name'] ?></td>
             <td><?php echo $user['ip'] ?></td>
             <td><?php echo $user['agent'] ?></td>
             <td><?php echo date('d/m/Y H:i', strtotime($user['insert'])) ?></td>
             <td><?php echo date('d/m/Y H:i', strtotime($user['update'])) ?></td>
             <td class="text-center"><i class="fa fa-<?php echo $user['active']? 'check text-success': 'close text-danger'; ?>"></i></td>
             <td><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/user?id=<?php echo $user['id'] ?>" class="btn btn-xs btn-danger" title="<?php echo leslie::translate('delete') ?>"><i class="glyphicon glyphicon-remove"</a></td>
          </tr>
          <?php } ?>

        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
   $(document).ready(function() {
      
      var $datatable = $('#datatable');

      $datatable.dataTable({
         language: {
             url: '<?php echo FRAMEWORK_URL_PLUG ?>/datatables/languages/italian.json'
         },
         //'order': [[ 2, 'asc' ]],
         order: [],
         columnDefs: [
            { orderable: false, targets: 2 }
          ]
      });

    });
 </script>