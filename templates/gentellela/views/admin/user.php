<div class="">
   <div class="page-title">
     <div class="title_left">
       <h3><?php echo $this->title ?></h3>
     </div>
   </div>
   <div class="clearfix"></div>
   <div class="row">
      <div class="col-md-6 col-xs-12">
         <div class="x_panel">
            <div class="x_title">
              <h2>Form Basic Elements <small>different form elements</small></h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
               <br />
               <form method="post" class="form-horizontal form-label-left" data-parsley-validate>
                  <input type="hidden" name="location" value="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/users">
                  <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12">E-mail</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" value="<?php echo $this->item['email'] ?>" readonly="readonly" class="form-control" placeholder="Default Input" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Stato</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div id="gender" class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default<?php if ($this->item['disabled']) { echo ' active'; } ?>" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                          <input type="radio" name="items[users][disabled]" value="1"<?php if (!$this->item['disabled']) { echo ' checked'; } ?>> &nbsp; Disabilitato &nbsp;
                        </label>
                        <label class="btn btn-success<?php if (!$this->item['disabled']) { echo ' active'; } ?>" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                          <input type="radio" name="items[users][disabled]" value="0"<?php if ($this->item['disabled']) { echo ' checked'; } ?>> Abilitato
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                     <label class="control-label col-md-3 col-sm-3 col-xs-12">Contenuto</label>
                     <div class="col-md-9 col-sm-9 col-xs-12">
                       <select name="items[users][content]" class="select2_single form-control" tabindex="-1" required>
                         <option value="0"></option>
                         <?php foreach ($this->contents as $content) { ?>
                         <option value="<?php echo $content['id'] ?>"<?php if ($this->item['content'] == $content['id']) { echo ' selected="selected"'; } ?>><?php echo $content['name'] ?></option>
                         <?php } ?>
                       </select>
                     </div>
                   </div>


                 <div class="ln_solid"></div>
                 <div class="form-group">
                   <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                     <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/users" class="btn btn-primary">Annulla</a>
                     <button type="submit" name="action" value="<?php echo $this->action ?>" class="btn btn-success"><?php echo \leslie::translate($this->action) ?></button>
                   </div>
                 </div>

               </form>
            </div>
         </div>
      </div>
   </div>
 </div>