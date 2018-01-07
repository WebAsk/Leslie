<div class="navbar-header">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
   <a class="navbar-brand" href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/index"><img src="<?php echo $GLOBALS['PROJECT']['LOGO'] ?>" height="20" alt="<?php echo $GLOBALS['PROJECT']['NAME']; ?>"></a>
</div>
<div id="navbar" class="navbar-collapse collapse">
   <ul class="nav navbar-nav">
      <?php foreach ($this->nav as $item) { ?>
      <li class="dropdown<?php if ($this->current == $item['plural']) { echo ' active'; } ?>">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo leslie::translate($item['plural']) ?> <span class="caret"></span></a>
         <ul class="dropdown-menu">
            <?php
            foreach ($item['items'] as $type) {
               echo '<li><a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '/admin/items?type=' . $type['id'] . '">' . leslie::translate($type['plural']) . '</a></li>' . PHP_EOL;
            }
            ?>
         </ul>
      </li>
      <?php } ?>
      <form method="get" action="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/search" class="navbar-form navbar-left" role="search">
         <div class="form-group">
            <input type="text" name="keywords" class="form-control" placeholder="<?php echo leslie::translate('search') ?>..." required>
         </div>
         <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
      </form>
      <?php foreach ($GLOBALS['PROJECT']['MODULES'] as $module) { ?>
      <li><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/<?php echo $module['name'] ?>"><?php echo ucfirst(leslie::translate($module['name'])) ?></a></li>
      <?php } ?>
   </ul>

   <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>"><?php echo leslie::translate('site') ?></a></li>
      <li><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/account/out" title="<?php echo leslie::translate('exit') ?>"><i class="glyphicon glyphicon-off"></i></a></li>
   </ul>
</div>