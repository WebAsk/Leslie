
<?php foreach (leslie::$alerts as $type => $mess) { ?>
<div class="alert alert-<?php echo $type ?>">
   <i class="glyphicon glyphicon-alert"></i> <?php echo $mess ?>.
</div>
<?php } ?>

