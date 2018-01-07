<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        
        <div class="x_title">
           <h2>Link permanenti <small>Lista</small></h2>
           <div class="clearfix"></div>
        </div>
        
        <div class="x_content">
            <p class="text-muted font-13 m-b-30">
                Friendly URL generati automaticamente dai processi di inserimento e modifica del contenuto in ordine cronologico. <br>
                L'ultimo link permanete registrato corrisponde a quello effettivamente assegnato al contenuto, quindi i restanti successivi permettono di recuperare il contenuto tramite un redirect 301.<br>
                &Egrave; possibile aggiungere nuovi link in modo da ampliare lo spettro di redirect automatici verso il contenuto.<br>
                Si sceglie l'effettivo link da assegnare al contenuto trascinandolo in prima posizione.<br>
                <strong>Attenzione:</strong> eleminando un record il contenuto non sar&agrave; pi&ugrave; accessibile secondo quella specifica stringa, quindi il sistema generer&agrave; un errore 404.
            </p>
            <form method="post" data-parsley-validate class="form-inline">
                <input type="hidden" name="refresh" value="1">
                <div class="form-group">
                    <input type="text" name="items[items_permalinks][value]" placeholder="permalink-string-value" class="form-control" required>
                    <input type="hidden" name="items[items_permalinks][type]" value="<?php echo $this->item['item_types']['id'] ?>" />
                </div>
                <div class="form-group">
                   <select name="items[items_permalinks][item]" class="form-control">
                       <?php foreach ($this->item['items_languages'] as $language) { ?>
                       <option value="<?php echo $language['id'] ?>"><?php echo htmlentities($language['title']) ?></option>
                       <?php } ?>
                   </select>
               </div>
                <button type="submit" name="action" value="insert" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Aggiungi permalink</button>
            </form>
            <hr>
            <form method="post" class="form-inline" id="items">
                <input type="hidden" name="refresh" value="1">
                <button type="submit" name="action" value="delete" class="btn btn-danger" title="<?php echo leslie::translate('delete') ?>"><i class="glyphicon glyphicon-remove"></i></button>
                <?php echo $this->actions ?>
                <hr>
                <table id="datatable-checkbox" class="table table-bordered bulk_action">
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox" id="check-all" class="flat"></th>
                            <th class="text-center"><?php echo leslie::translate('Order') ?></th>
                            <th class="text-center"><?php echo leslie::translate('Language') ?></th>
                            <th><?php echo leslie::translate('Value') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->items as $key => $item) { ?>
                        <tr<?php if ($key == 0) { echo ' class="bg-success'; } ?>">

                            <td class="text-center">
                                <input type="checkbox" name="items[items_permalinks][id][]" id="item_check_<?php echo $item['id'] ?>" value="<?php echo $item['id'] ?>" class="flat">
                            </td>
                            <td class="text-center">
                                <i class="glyphicon glyphicon-sort items_sort_handle"></i>
                            </td>
                            <td class="text-center">
                                <img src="<?php echo FRAMEWORK_URL_IMG . '/languages/' . $this->languages[$item['id_language']]['sign'] ?>.png" />
                            </td>
                            <td>
                                <a href="#" onclick="javascript: window.open('<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/<?php echo leslie::translate('contents') ?>/<?php echo $item['value'] ?>', '<?php echo $item['value'] ?>', 'fullscreen=yes')"><?php echo $item['value'] ?></a>
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0)" onclick="delete_item(<?php echo $item['id'] ?>)" class="btn btn-xs btn-danger" title="<?php echo leslie::translate('delete') ?>"><i class="glyphicon glyphicon-remove"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
               </table>
           </form>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">


        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        var $datatable = $('#datatable-checkbox');

        $datatable.dataTable({
            language: {
                url: '<?php echo FRAMEWORK_URL_PLUG ?>/datatables/languages/italian.json'
            },
            //'order': [[ 2, 'asc' ]],
            "ordering": false,
            'columnDefs': [
                { 
                    orderable: false, 
                    targets: [0,1,2,3] 
                }
            ]
        });

        $datatable.on('draw.dt', function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_flat-green'
            });
        });

    });
 </script>