<div class="row">
    <div class="col-xs-12">
        
        <?php if (!empty($GLOBALS['PROJECT']['LOGO'])) { ?>
            <img src="<?php echo $GLOBALS['PROJECT']['LOGO'] ?>" alt="<?php echo $GLOBALS['PROJECT']['NAME'] ?> logo">
        <?php } ?>
        
        <div id="project-name">
            <?php echo $GLOBALS['PROJECT']['NAME'] ?>
        </div>
        
        <?php if (!empty($GLOBALS['PROJECT']['SLOGAN'])) { ?>
            <div><?php echo htmlentities($GLOBALS['PROJECT']['SLOGAN']) ?></div>
        <?php } ?>
            
    </div>
</div>
    