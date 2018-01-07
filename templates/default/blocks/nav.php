<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".js-navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
</div>
<div class="collapse navbar-collapse js-navbar-collapse">
    <ul class="nav navbar-nav">
       <li<?php if ($this->current == 'home') { echo ' class="active"'; } ?>><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>">Home</a></li>   
    </ul>
    <ul class="nav navbar-nav">
       <?php 
       foreach ($this->joint_contents as $type_id => $joint_type) { 
          echo '<li class="dropdown dropdown-large"';
          if ($this->current == $this->types[$type_id]['plural']) {
             echo ' class="active"';
          }
          echo '>' . PHP_EOL;
          echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . ucfirst(leslie::translate($this->types[$type_id]['plural'])) . ' <span class="caret"></span></a>' . PHP_EOL;
          echo '<ul class="dropdown-menu dropdown-menu-large row">' . PHP_EOL;

          foreach ($joint_type as $type_id => $joints) {
             echo '<li class="col-sm-6" style="min-width: 230px">' . PHP_EOL;
             echo '<ul>' . PHP_EOL;
             echo '<li class="dropdown-header">' . ucfirst(leslie::translate($this->types[$type_id]['plural'])) . '</li>' . PHP_EOL;
             foreach ($joints as $joint) {
                echo '<li>' . PHP_EOL;
                echo '<a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '/' . leslie::translate('contents') . '/' . $joint['permalink'] . '">' . htmlentities($joint['title']) . '</a>' . PHP_EOL;
                echo '</li>' . PHP_EOL;            
             }
             echo '</ul>' . PHP_EOL;
             echo '</li>' . PHP_EOL;
          }

          echo '</ul>' . PHP_EOL;

          echo '</li>' . PHP_EOL;
       }
       ?>
    </ul>

    <ul class="nav navbar-nav">
       <?php foreach ($this->unjoint_contents as $content) { ?>
       <li<?php if ($this->current == $content['permalink']) { echo ' class="active"'; } ?>><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] . '/' . leslie::translate('contents') . '/' . $content['permalink'] ?>"><?php echo $content['title'] ?></a></li>   
       <?php } ?>
    </ul>



   <ul class="nav navbar-nav">

      <li<?php if ($this->current == 'contacts') { echo ' class="active"'; } ?>><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] . '/' . leslie::translate('contacts') ?>"><?php echo leslie::translate('Contacts') ?></a></li>
   </ul>
   <!--
   <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/account/index">Account</a></li>  
   </ul>
   -->   
   <form method="get" action="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/<?php echo leslie::translate('contents') ?>/<?php echo leslie::translate('search') ?>" class="navbar-form navbar-right form-inline" role="search">
      <input type="text" name="q" class="form-control" placeholder="<?php echo leslie::translate('search') ?>..." required>
      <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
   </form>
</div>