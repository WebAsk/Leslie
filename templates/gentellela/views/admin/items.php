<?php $this->styles[] = FRAMEWORK_URL_TPL . '/gentellela/styles/admin/items.css' ?>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">

        <div class="x_title">
            <form method="get" action="items" id="params">
                <input type="hidden" name="type" value="<?php echo $this->item['type']['id'] ?>">
                <input type="hidden" name="order" value="<?php echo $this->params['order'] ?>" id="order">
                <div class="form-inline">
                   <div class="form-group" style="margin: 5px 5px 0 0">
                      <input type="text" name="string" value="<?php echo $this->params['string'] ?>" class="form-control pull-right" placeholder="Stringa di ricerca...">
                   </div>
                    <?php if (count($this->states) > 1) { ?>
                    <div class="form-group" style="margin: 5px 5px 0 0">
                      <select name="state" class="form-control" onchange="$('#params').submit()">
                         <?php foreach ($this->states as $id => $val) { ?>
                         <option value="<?php echo $id ?>"<?php if ($this->params['state'] == $id) { echo ' selected';} ?>><?php echo \leslie::translate($val['value']) ?></option>
                         <?php } ?>
                      </select>
                   </div>
                    <?php } ?>
                   <div class="form-group" style="margin: 5px 5px 0 0">
                      <select name="limit" class="form-control" onchange="$('#params').submit()">
                         <?php foreach ($this->limits as $val => $txt) { ?>
                         <option value="<?php echo $val ?>"<?php if ($this->params['limit'] == $val) { echo ' selected';} ?>><?php echo $txt ?> risultati per pagina</option>
                         <?php } ?>
                      </select>
                   </div>
                   <div class="form-group" style="margin: 5px 5px 0 0">
                      <div class="btn-group">
                         <?php for ($pag = 1; $pag <= $this->pages; $pag++) { ?>
                         <button type="submit" name="page" value="<?php echo $pag ?>" class="btn btn-success<?php if ($this->params['page'] == $pag) { echo ' active'; } ?>" data-toggle="tooltip" data-placement="bottom" title="Pagina <?php echo $pag ?>"><?php echo $pag ?></button>
                         <?php } ?>
                      </div>
                   </div>
                </div>
            </form>
        </div>
        <div class="x_content">
            <form method="post" id="items_form">
                <div class="form-inline">
                   <div class="form-group">
                      <?php if (!empty($this->actions)) { ?>
                      <?php echo $this->actions ?>
                      <?php } ?>
                      <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/item/handle?type=<?php echo $this->item['type']['id'] ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="<?php echo leslie::translate('new') ?>"><i class="glyphicon glyphicon-plus"></i></a>
                      <?php if ($this->item['documents']) { ?>
                      <a href="uploads/<?php echo $this->item['type']['id'] ?>" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="<?php echo leslie::translate('new') ?>">Upload multiplo</a>
                      <?php } ?>
                      <button type="submit" name="action" value="delete" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="<?php echo leslie::translate('delete') ?>"><i class="glyphicon glyphicon-remove"></i></button>
                      <?php if ($this->user['type'] == 1) { ?>
                      <button type="submit" name="action" value="restore" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="<?php echo leslie::translate('Restore') ?>"><i class="glyphicon glyphicon-ok"></i></button>
                      <?php } ?>
                   </div>
                </div>
                
                <input type="hidden" name="refresh" value="1">
                <hr>
                
                <table id="items" class="table table-striped table-bordered bulk_action">
                  <thead>
                     <tr>
                        <th class="text-center"><input type="checkbox" id="check-all" class="flat"></th>
                        <?php if (!empty($this->order)) { ?>
                        <th class="text-center">
                           <a href="javascript:void(0)" onclick="$('#order').val('order'); $('#params').submit()" data-toggle="tooltip" data-placement="top" title="Ordina per ordine"><?php echo strtoupper(leslie::translate('order')) ?></a>
                           <?php if ($this->params['order'] == 'order') { ?>&nbsp;<i class="glyphicon glyphicon-sort-by-order pull-right"></i><?php } ?>
                        </th>
                        <?php } ?>
                        <th><a href="javascript:void(0)" onclick="$('#order').val('name'); $('#params').submit()" data-toggle="tooltip" data-placement="top" title="Ordina per nome"><?php echo strtoupper(leslie::translate('name')) ?></a><?php if ($this->params['order'] == 'name') { ?>&nbsp;<i class="glyphicon glyphicon-sort-by-alphabet pull-right"></i><?php } ?></th>
                                                
                        <?php if (isset($this->items[0])) { ?>
                            <?php foreach ($this->items[0] as $key => $val) { ?>
                                <?php if (!in_array($key, $this->columns_excluded)) { ?>
                                    <?php if ($key != 'state' || ($key == 'state' && count($this->states) > 1)) { ?>
                                        <th<?php if ($key != 'name') { ?> class="text-center"<?php } ?>><a href="javascript:void(0)" onclick="$('#order').val('<?php echo $key ?>'); $('#params').submit()" data-toggle="tooltip" data-placement="top" title="Ordina per <?php echo $key ?>"><?php echo strtoupper(leslie::translate($key)) ?></a><?php if ($this->params['order'] == $key) { ?>&nbsp;<i class="glyphicon glyphicon-sort-by-attributes pull-right"></i><?php } ?></th>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        
                        
                        <th class="text-center"><?php echo strtoupper(leslie::translate('contents')) ?></th>
                        <th></th>
                     </tr>
                  </thead>


                  <tbody>
                     <?php foreach ($this->items as $item) { ?>
                     <tr<?php if ($item['active'] == 0) { echo ' class="danger"'; } ?>>

                        <td class="text-center">
                           <input type="checkbox" name="items[<?php echo $this->item['type']['prefix'] ?>_list][code][]" id="item_check_<?php echo $item['code'] ?>" value="<?php echo $item['code'] ?>" class="item_check flat">
                        </td>
                        <?php if (!empty($this->order)) { ?>
                        <td class="text-center">
                           <?php if ($this->params['order'] == 'order') { ?>
                           <i class="glyphicon glyphicon-sort items_sort_handle"></i>
                           <?php } ?>
                        </td>
                        <?php } ?>
                        <td>

                           <?php if ($this->item['documents']) { ?>
                            <?php if (!empty($this->documents['images'])) { ?>
                            <img src="<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/' . $this->item['type']['plural'] ?><?php if (!empty($this->documents['folder'])) { echo '/' . $this->documents['folder']; } ?>/<?php echo $item['name'] ?>" class="items_image">
                            <?php } else { ?>
                            <a href="#" onclick="javascript: window.open('<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/' . $this->item['type']['plural'] . '/' . $item['name'] ?>', '<?php echo $item['name'] ?>', 'fullscreen=yes')"><i class="fa fa-<?php echo $this->documents['icon'] ?>"></i> <?php echo $item['name'] ?></a>
                            <?php } ?>
                           <?php } else { ?>
                           <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/item/view?type=<?php echo $this->item['type']['id'] ?>&code=<?php echo $item['code'] ?>" class="items_view" data-toggle="modal" data-target=".bs-example-modal-lg"><?php echo htmlentities($item['name']) ?></a>
                           <?php } unset($item['name']) ?>
                        </td>
                        
                        <?php foreach ($item as $key => $val) { ?>
                            <?php if (!in_array($key, $this->columns_excluded)) { ?>
                                <?php if (count($this->states) > 1 && $key == 'state' && !empty($val)) { ?>
                                    <td class="text-center"><span class="label label-<?php echo $this->states[$val]['view']? 'success': 'warning' ?>"><?php echo \leslie::translate($this->states[$val]['value']) ?></span></td>
                                <?php } else if ($key != 'state') { ?>
                                    <td<?php if ($key != 'name') { ?> class="text-center"<?php } ?>><?php echo htmlentities($val) ?></td>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                                    
                        <?php $item['user_type'] > $this->user['type'] || $item['id_user'] == $this->user['id'] || !empty($this->user['super'])? $actions = true: $actions = false ?>
                        
                        <td class="text-center">
                            <?php if ($item['active'] == 1 && $actions) { ?>
                                <?php foreach ($this->languages as $language) { ?>
                                    <?php $language_exist = in_array($language['id'], $this->items_languages[$item['id']]) ?>
                                    <a href="<?php echo !isset($this->states[$item['state']]['edit']) || $this->states[$item['state']]['edit']? $GLOBALS['PROJECT']['URL']['BASE'] . '/admin/item/handle?type=' . $this->item['type']['id'] . '&lang=' . $language['id'] . '&code=' . $item['code']: 'javascript:void(0)' ?>" class="item['languages']" title="<?php echo $language_exist? \leslie::translate('update'): \leslie::translate('insert') ?>">
                                       <img src="<?php echo FRAMEWORK_URL_IMG . '/languages/' . $language['sign'] ?>.png"<?php if (!$language_exist || (isset($this->states[$item['state']]['edit']) && $this->states[$item['state']]['edit'] == 0)) { ?> style="opacity: 0.5; filter: alpha(opacity=50)"<?php } ?>>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        </td>
                        
                        <td class="text-center">
                            <?php if ($actions) { ?>
                                <?php if (!empty($this->item['type']['permalink'])) { ?>
                                <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/item/permalinks?type=<?php echo $this->item['type']['id'] ?>&code=<?php echo $item['code'] ?>" class="btn btn-xs btn-warning" title="permalinks"><i class="glyphicon glyphicon-link"></i></a>
                                <?php } ?>
                                <?php if (!empty($this->item['type']['notice'])) { ?>
                                <a href="javascript: void(0)" onclick="confirm_href('confermi di voler inviare una notifica agli accounts collegati a questo elemento?', '<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/items?action=notice&type=<?php echo $this->item['type']['id'] ?>&code=<?php echo $item['code'] ?>')" class="btn btn-xs btn-info" title="<?php echo \leslie::translate('notice') ?>"><i class="glyphicon glyphicon-send"></i></a>
                                <?php } ?>
                                <?php if ($item['active'] == 1) { ?>
                                <a href="javascript:void(0)" onclick="delete_item('<?php echo $item['code'] ?>')" class="btn btn-xs btn-danger" title="<?php echo leslie::translate('delete') ?>"><i class="glyphicon glyphicon-remove"></i></a>
                                <?php } else if ($item['active'] == 0) { ?>
                                <a href="javascript:void(0)" onclick="restore_item('<?php echo $item['code'] ?>')" class="btn btn-xs btn-success" title="<?php echo leslie::translate('Restore') ?>"><i class="glyphicon glyphicon-ok"></i></a>
                                <?php } ?>
                            <?php } ?>
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