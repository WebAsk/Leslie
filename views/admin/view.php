
<style>
   .list-inline > li {
      border-left-width: 1px;
      border-left-style: solid;
   }
   .list-inline > li:first-child {
      border-left: none;
   }
   .list-inline img {
       height: 80px;
   }
   
</style>

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
</button>
<h4 class="modal-title" id="myModalLabel"><?php echo htmlentities($this->item['name']) ?></h4>
</div>
<div class="modal-body" style="overflow: auto">
   <h3>
      <?php echo \leslie::translate( 'Contents') ?>
   </h3>
   <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
      <?php foreach ($this->item['languages'] as $key => $item) { ?>
      <div class="panel">
         
         <a class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $item['id_language'] ?>" aria-expanded="true" aria-controls="collapse<?php echo $item['id_language'] ?>">
            <img src="<?php echo FRAMEWORK_URL_IMG ?>/languages/<?php echo $this->languages[$item['id_language']]['sign'] ?>.png" alt="">
         </a>
         <div id="collapse<?php echo $item['id_language'] ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
               <h2><?php echo htmlentities($item['title']) ?></h2>
               <p><?php echo htmlentities($item['intro']) ?></p>
               <div style="max-height: 300px; overflow-y: scroll"><?php echo $item['description'] ?></div>
               <hr>
               <ul class="list-group">
                  <li class="list-group-item"><strong>Inserimento</strong>: <?php echo date('d/m/Y H:i', strtotime($item['insert'])) ?></li>
                  <li class="list-group-item"><strong>Modificato</strong>: <?php echo date('d/m/Y H:i', strtotime($item['update'])) ?></li>
                  <li class="list-group-item"><strong>Visualizzazioni</strong>: <?php echo $item['views'] ?></li>
               </ul>
            </div>
         </div>
      </div>
      <?php } ?>
       <ul class="list-group">
        <?php if (!empty($this->item['type']['states'][$this->item['state']])) { ?>
        <li class="list-group-item"><strong>Stato</strong>: <?php echo \leslie::translate($this->item['type']['states'][$this->item['state']]['value']) ?></li>
        <?php } ?>
     </ul>
   </div>
   <?php if (!empty($this->item['joints'])) { ?>
   <h3><?php echo ucfirst(\leslie::translate('Joints')) ?></h3>
   <?php foreach ($this->item['joints'] as $type) { ?>
   <ul>
      <li><strong><?php echo ucfirst(\leslie::translate($type['plural'])) ?></strong>
         <ul<?php if ($type['documents']) { echo ' class="list-inline"'; } ?>>
         <?php foreach ($type['items'] as $item) { ?>
            <li><?php echo $type['documents']?  '<img src="' . $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/' . $type['plural'] . '/' . $type['folder'] . '/' . $item['name'] . '">': htmlentities($item['name']) ?></li>
         <?php } ?>
         </ul>
      </li>
   </ul>
   <?php } ?>
   <?php } ?>
   <?php if (!empty($this->item['fields'])) { ?>
   <h3><?php echo ucfirst(\leslie::translate('Attributes')) ?></h3>
   <ul>
      <?php foreach ($this->item['fields'] as $key => $item) { ?>
      <li><strong><?php echo ucfirst(\leslie::translate($item['label'])) ?></strong>: <?php echo htmlentities($item['value']) ?></li>
      <?php } ?>
   </ul>
   <?php } ?>
   <?php if (!empty($this->item['account'])) { ?>
   <h3>Account</h3>
   <ul>
      <?php foreach ($this->item['account'] as $key => $val) { ?>
      <li><strong><?php echo ucfirst(\leslie::translate($key)) ?></strong>: <?php echo htmlentities($val) ?></li>
      <?php } ?>
   </ul>
   <?php } ?>
</div>
<div class="modal-footer">
 <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo \leslie::translate('Close') ?></button>
</div>