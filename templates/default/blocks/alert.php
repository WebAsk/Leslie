<?php if (!empty(\leslie::$alerts)) { ?>
    <?php foreach (\leslie::$alerts as $key => $val) { ?>
        <?php if (is_array($val)) { foreach ($val as $message) { ?>
            <div class="alert alert-<?php echo $key ?>"><?php echo ucfirst($message) ?></div>
        <?php } } else { ?>
            <div class="alert alert-<?php echo $key ?>"><?php echo ucfirst($val) ?></div>
        <?php } ?>
    <?php } ?>
<?php } ?>
