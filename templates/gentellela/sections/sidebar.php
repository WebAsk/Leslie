<div class="left_col scroll-view">
   <div class="navbar nav_title" style="border: 0;">
     <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin" class="site_title"><i class="fa fa-gears"></i> <span><?php echo $GLOBALS['PROJECT']['NAME'] ?></span></a>
   </div>

   <div class="clearfix"></div>

   <!-- menu profile quick info -->
   <div class="profile">
     <div class="profile_pic">
       <img src="<?php echo empty($this->user['image'])? FRAMEWORK_URL_TPL . '/gentellela/production/images/img.jpg': $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/images/small/' . $this->user['image'] ?>" alt="<?php echo $this->user['name'] ?>" class="img-circle profile_img">
     </div>
     <div class="profile_info">
       <span><?php echo \leslie::translate('Welcome') ?>,</span>
       <h2><?php echo $this->user['name'] ?></h2>
     </div>
   </div>
   <!-- /menu profile quick info -->

   <br />

   <!-- sidebar menu -->
   <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
     <div class="menu_section">
       <div class="clearfix"></div>
       <ul class="nav side-menu">
         <li><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin"><i class="fa fa-tachometer"></i> Dashboard</a></li>
         <?php foreach ($this->nav as $item) { ?>
         <li<?php if ($this->current == $item['plural']) { echo ' class="active"'; } ?>>
            <a>
               <i class="fa fa-<?php echo $item['icon'] ?>"></i> <?php echo ucfirst(leslie::translate($item['plural'])) ?> <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu"<?php if ($this->current == $item['plural']) { echo ' style="display: block"'; } ?>>
               <?php
               foreach ($item['items'] as $type) {
                  echo '<li><a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '/admin/items?type=' . $type['id'] . '">' . ucfirst(leslie::translate($type['plural'])) . '</a></li>' . PHP_EOL;
               }
               ?>
            </ul>
         </li>
         <?php } ?>
         <?php foreach ($GLOBALS['PROJECT']['MODULES'] as $item) { ?>
         <?php if ($item['permits'] >= $this->user['type']) { ?>
         <li<?php if ($this->current == $item['name']) { echo ' class="active"'; } ?>>
            <a>
               <i class="fa fa-<?php echo $item['icon'] ?>"></i> <?php echo \leslie::translate(ucfirst($item['name'])) ?>
               <?php if (!empty($item['methods'])) { ?>
               <span class="fa fa-chevron-down"></span>
               <?php } ?>
            </a>
            <?php if (!empty($item['methods'])) { ?>
            <ul class="nav child_menu"<?php if ($this->current == $item['name']) { echo ' style="display: block"'; } ?>>
               <?php
               foreach ($item['methods'] as $type) {
                  echo '<li><a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '/admin/' . $type['name'] . '">' . ucfirst(leslie::translate($type['name'])) . '</a></li>' . PHP_EOL;
               }
               ?>
            </ul>
            <?php } ?>
         </li>
         <?php } ?>
         <?php } ?>
       </ul>
     </div>
   </div>
   <!-- /sidebar menu -->

   <!-- /menu footer buttons -->
   <div class="sidebar-footer hidden-small">
     <a data-toggle="tooltip" data-placement="top" title="Settings">
       <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
     </a>
     <a data-toggle="tooltip" data-placement="top" title="FullScreen">
       <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
     </a>
     <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>" data-toggle="tooltip" data-placement="top" title="Site">
       <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
     </a>
     <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/out" data-toggle="tooltip" data-placement="top" title="Logout">
       <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
     </a>
   </div>
   <!-- /menu footer buttons -->
 </div>