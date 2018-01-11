<?php if (!empty(\leslie::$alerts)) {
   foreach (\leslie::$alerts as $type => $message) { ?>
      <div class="alert alert-<?php echo $type ?>">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
         <?php if (!is_array($message)) { ?>
         <strong><?php echo \leslie::translate($message) ?></strong>
         <?php } else { 
         foreach ($message as $mess) { ?>
            <strong<?php echo \leslie::translate($mess) ?></strong>
         <?php } 
      } ?>
      </div>
   <?php } 
} ?>