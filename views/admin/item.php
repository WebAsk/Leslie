
<div class="page-header">
    <h1><?php echo $this->title ?></h1>
</div>

<form method="post" enctype="multipart/form-data" id="item" class="form-horizontal panel-group">
    <input type="hidden" name="location" id="item_location" value="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] . '/admin/items?type=' . $this->item['item_types']['id'] ?>">
    <div class="row" id="masonry_container">
        <div class="col-md-6 masonry_items">
            <fieldset class="panel panel-default">
                <div class="panel-heading"><?php echo leslie::translate('Basic info') ?></div>
                <div class="panel-body">
                    
                    <?php if (!$this->item['documents']) { ?>
                        <div class="form-group">
                            <label for="contents_name" class="col-sm-2 control-label"><?php echo leslie::translate('Name') ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="items[items_list][name]" value="<?php if (!empty($this->item['items_list']['name'])) { echo htmlspecialchars($this->item['items_list']['name']); } ?>" id="contents_name" class="form-control" placeholder="<?php echo leslie::translate('name') ?>" required>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="contents_code" class="col-sm-2 control-label"><?php echo leslie::translate('Code') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="items[items_list][code]" value="<?php if (!empty($this->item['items_list']['code'])) { echo htmlspecialchars($this->item['items_list']['code']); } ?>" id="contents_code" class="form-control" placeholder="<?php echo leslie::translate('code') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="items_languages_title" class="col-sm-2 control-label"><?php echo leslie::translate('Title') ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="items[items_languages][title]" value="<?php if (!empty($this->item['items_languages']['title'])) { echo htmlspecialchars($this->item['items_languages']['title']); } ?>" id="items_languages_title" class="form-control" placeholder="<?php echo leslie::translate('title') ?>" required>
                        </div>
                    </div>
                    <?php if ($this->item['item_types']['intro']) { ?>
                        <div class="form-group">
                           <label for="intro" class="col-sm-2 control-label"><?php echo leslie::translate('Introduzione') ?></label>
                           <div class="col-sm-10">
                              <textarea name="items[items_languages][intro]" maxlength="255" id="intro" class="form-control" placeholder="<?php echo leslie::translate('Introduction') ?>" required><?php if (!empty($this->item['items_languages']['intro'])) { echo htmlspecialchars($this->item['items_languages']['intro']); } ?></textarea>
                           </div>
                        </div>
                    <?php } ?>
                </div>
            </fieldset>
        </div>

        <?php if (!empty($this->states)) { ?>
        <div class="col-md-6 masonry_items">
            <fieldset class="panel panel-default">
                <div class="panel-heading"><?php echo leslie::translate('States') ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="items_states" class="col-sm-2 control-label"><?php echo leslie::translate('States') ?></label>
                        <div class="col-sm-10">
                            <select name="items[items_list][state]" class="form-control" id="items_states" required>
                                <option value="" selected disabled style="background-color: #f8f8f8"><?php echo leslie::translate('Select') ?></option>
                                <?php foreach ($this->states as $state) { ?>
                                    <option value="<?php echo $state['id'] ?>"<?php if (!empty($this->item['items_list']['state']) && $state['id'] == $this->item['items_list']['state']) { echo ' selected'; } ?>><?php echo leslie::translate($state['value']) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <?php } ?>

        <?php if (!empty($this->joints)) { ?>
        <div class="col-md-6 masonry_items">
            <fieldset class="panel panel-default">
                <div class="panel-heading"><?php echo leslie::translate('joints') ?></div>
                <div class="panel-body">
                    <?php foreach ($this->joints as $key => $joint_type) { ?>
                        <?php $joint_type['multiple']? $joint_type_name = $joint_type['plural']: $joint_type_name = $joint_type['singular'] ?>
                        <div class="form-group">
                            <label for="items_joints_id_joint" class="col-sm-2 control-label"><?php echo leslie::translate($joint_type_name) ?></label>
                            <div class="col-sm-10">
                                <select name="items[items_joints][<?php echo $joint_type['id'] ?>][]" <?php echo $joint_type['multiple']? ' multiple class="chosen-select form-control"': ' class="form-control"'  ?> id="items_joints_id_joint">
                                    <?php if (!$joint_type['multiple']) { ?>
                                    <option value="" selected disabled style="background-color: #f8f8f8"><?php echo leslie::translate('Select') ?></option>
                                    <?php } ?>
                                    <?php foreach ($this->joint_items[$joint_type['id']] as $item) { ?>
                                    <option 
                                        value="<?php echo $item['id'] ?>"
                                        <?php if (isset($this->item['items_joints']) && in_array($item['id'], $this->item['items_joints'][$joint_type['id']])) { echo ' selected'; }
                                        if (!empty($joint_type['images'])) { echo ' data-img-src="'.$GLOBALS['PROJECT']['URL']['DOCUMENTS'].'/' . $joint_type['plural'];
                                        if (!empty($this->folders[$joint_type['id']])) { echo  '/' . $this->folders[$joint_type['id']]; } echo '/' . $item['name'] . '"'; } ?>
                                    ><?php echo empty($joint_type['images']) || empty($item['title'])? $item['name']: $item['title'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </fieldset>
        </div>
        <?php } ?>

        <?php if (!empty($this->fields)) { ?>
        <div class="col-md-6 masonry_items">        
            <fieldset class="panel panel-default">
                <div class="panel-heading"><?php echo leslie::translate('fields') ?></div>
                <div class="panel-body">
                    <?php foreach ($this->fields as $key => $field) { ?>
                    <div class="form-group">
                        <label for="items_joints_id_joint" class="col-sm-4 control-label"><?php echo htmlentities(ucfirst(\leslie::translate($field['label']))) ?></label>
                        <div class="col-sm-8">
                            <?php if ($field['type'] == 'textarea') { ?>
                                <textarea name="items[items_fields][<?php echo $key ?>][value]" class="form-control"<?php if ($field['required']) { echo ' required'; } ?> <?php echo $field['attributes'] ?><?php if (!empty($field['readonly'])) { echo ' readonly'; } ?>>
                                    <?php if (isset($this->item['items_fields'][$field['id']])) { echo htmlspecialchars($this->item['items_fields'][$field['id']]); } else if (isset($field['default'])) { echo $field['default']; } ?>
                                </textarea>  
                            <?php } else { ?>
                                <input type="<?php echo $field['type'] ?>" name="items[items_fields][<?php echo $key ?>][value]" value="<?php if (isset($this->item['items_fields'][$field['id']])) { echo htmlspecialchars($this->item['items_fields'][$field['id']]); } else if (isset($field['default'])) { echo $field['default']; } ?>" <?php echo $field['attributes'] ?> placeholder="<?php echo htmlspecialchars(leslie::translate($field['placeholder'])) ?>" class="form-control"<?php if ($field['required']) { echo ' required'; } ?><?php if (!empty($field['readonly'])) { echo ' readonly'; } ?>>

                            <?php } ?>
                            <input type="hidden" name="items[items_fields][<?php echo $key ?>][id_type]" value="<?php echo $field['id'] ?>">
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </fieldset>
        </div>
        <?php } ?>

    </div>
    
    <?php if ($this->item['documents']) { ?>
        <hr>
        <div class="row">
            <div class="col-md-12 masonry_items">
                <fieldset class="panel panel-default">
                    <div class="panel-heading"><?php echo leslie::translate('file') ?></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="document" class="col-sm-2 control-label"><?php echo leslie::translate('document') ?></label>
                            <div class="col-sm-10">
                                <input type="file" name="document" id="item_document"<?php if ($this->action == 'insert' && empty($this->item['name'])) { echo ' required'; } ?> class="form-control">
                            </div>
                        </div>

                        <?php if ($this->documents[0]['images']) { ?>

                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    <?php foreach ($this->documents as $document) { ?>
                                        <input type="hidden" name="crop[<?php echo $document['id'] ?>][x]" id="crop_<?php echo $document['id'] ?>_x">
                                        <input type="hidden" name="crop[<?php echo $document['id'] ?>][y]" id="crop_<?php echo $document['id'] ?>_y">
                                        <input type="hidden" name="crop[<?php echo $document['id'] ?>][w]" id="crop_<?php echo $document['id'] ?>_w">
                                        <input type="hidden" name="crop[<?php echo $document['id'] ?>][h]" id="crop_<?php echo $document['id'] ?>_h">
                                        <div class="col-md-<?php echo 12 / count($this->documents) ?>">
                                            <img<?php if (!empty($this->item['items_list']['name'])) { echo ' src="' . $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/' . $this->item['item_types']['plural']; if (!empty($document['folder'])) { echo '/' . $document['folder']; } echo '/'; echo $this->item['items_list']['name'] . '"'; } ?> id="item_image_preview_<?php echo $document['id'] ?>" style="max-width: 100%<?php if (empty($this->item['items_list']['name'])) { echo '; display: none'; } ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>


                            <script>

                                $(function () {
                                    if ($('#item_document').length) {
                                        <?php foreach ($this->documents as $document) { ?>
                                            var $image<?php echo $document['id'] ?> = $('#item_image_preview_<?php echo $document['id'] ?>');
                                        <?php } ?>
                                        $("#item_document").change(function(){
                                            if (this.files && this.files[0]) {

                                                var reader = new FileReader();

                                                <?php foreach ($this->documents as $document) { ?>
                                                    $image<?php echo $document['id'] ?>.cropper('destroy');
                                                <?php } ?>

                                                reader.onload = function (e) {

                                                    <?php foreach ($this->documents as $document) { ?>
                                                        $image<?php echo $document['id'] ?>.css('display', 'block');
                                                        $image<?php echo $document['id'] ?>.attr('src', e.target.result);
                                                        $image<?php echo $document['id'] ?>.cropper({
                                                            aspectRatio: <?php echo $document['width'] . ' / ' . $document['height'] ?>,
                                                            zoomOnWheel: false,
                                                            autoCropArea: 1,
                                                            crop: function(e) {
                                                                // Output the result data for cropping image.
                                                                $('#crop_<?php echo $document['id'] ?>_x').val(e.x);
                                                                $('#crop_<?php echo $document['id'] ?>_y').val(e.y);
                                                                $('#crop_<?php echo $document['id'] ?>_w').val(e.width);
                                                                $('#crop_<?php echo $document['id'] ?>_h').val(e.height);
                                                            }
                                                        });
                                                        //$masonry.masonry('reloadItems');
                                                    <?php } ?>
                                                }

                                                reader.readAsDataURL(this.files[0]);
                                            }
                                        });

                                    }
                                });
                            </script>
                       <?php } ?>

                    </div>
                </fieldset>
            </div>
        </div>
    <?php } ?>
    
    
    <?php if ($this->item['item_types']['description']) { ?>
    <hr>
    
    <div class="row">
        <div class="col-md-12">
            <fieldset class="panel panel-default">
                <div class="panel-heading"><?php echo leslie::translate('content') ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea name="items[items_languages][description]" id="items_languages_description" class="form-control" required><?php if (!empty($this->item['items_languages']['description'])) { echo  $this->item['items_languages']['description']; } ?></textarea>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    
    <script>
        $(function () {
            CKEDITOR.replace( 'items_languages_description', {
                <?php if (file_exists($GLOBALS['PROJECT']['PATHS']['ROOT'] . '/public/plugins/ckeditor/config.js')) { ?>
                customConfig: '<?php echo $GLOBALS['PROJECT']['URL']['BASE'] . '/plugins/ckeditor/config.js' ?>'
                <?php } else { ?>
                width: '100%'
                <?php } ?>
            });
        });
    </script>
    
    <?php } ?>
    <hr>
    <div class="row">
        <div class="col-md-12 text-right">
            <button type="submit" name="action" value="<?php echo $this->action ?>" class="btn btn-primary"><?php echo leslie::translate($this->action) ?></button>
        </div>
    </div>
</form>

<script>
    
    window.setTimeout(function () {
        var form = document.getElementById("item");
        var action = document.createElement("input");
        action.type = "hidden";
        action.name = "action";
        action.value = "<?php echo $this->action ?>";
        form.appendChild(action);
        document.getElementById('item_location').value = '<?php echo $this->url ?>';
        form.submit();
    }, <?php echo $GLOBALS['PROJECT']['SESSION']['TIME'] - 3 ?> * 1000);
    
</script>