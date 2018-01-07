<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
   <div class="top-area">
      <div class="container">
         <div class="row">
            <div class="col-sm-6 col-md-6">
            <p class="bold text-left"><?php echo ucfirst($GLOBALS['COMPANY']['HOURS']) ?></p>
            </div>
            <div class="col-sm-6 col-md-6">
               <p class="bold text-right"><?php echo leslie::translate('Call now') ?> <?php echo $GLOBALS['COMPANY']['TEL'] ?></p>
            </div>
         </div>
      </div>
   </div>
     <div class="container navigation">

         <div class="navbar-header page-scroll">
             <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                 <i class="fa fa-bars"></i>
             </button>
             <a class="navbar-brand" href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>">
                 <img src="<?php echo $GLOBALS['PROJECT']['URL']['IMAGES'] ?>/logos/logo-nav.png" alt="Logo Sandra Tretola" width="250" height="40" />
             </a>
         </div>

         <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
        <ul class="nav navbar-nav">
         <li class="active"><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>">Home</a></li>
        </ul>
         </div>
     </div>
 </nav>

