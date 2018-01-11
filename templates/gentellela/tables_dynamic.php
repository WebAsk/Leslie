<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/sections/head.php' ?>
    <!-- iCheck -->
    <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/sections/sidebar.php' ?>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/sections/topnav.php' ?>
        </div>
        <!-- /top navigation -->
        
        <!-- page content -->
        <div class="right_col" role="main">
           
          <div class="">
             
            <div class="page-title">
              <div class="title_left">
                 <h3><?php echo ucfirst(\leslie::translate($this->title)) ?> <small><?php echo $this->description ?></small></h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <form action="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/admin/search" method="get">
                  <div class="input-group">
                    <input type="text" name="keywords" class="form-control" placeholder="<?php echo \leslie::translate('Global search') ?>...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="submit">Go!</button>
                    </span>
                  </div>
                </form>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
            
            <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/sections/alert.php' ?>

            <div class="row">
               
               <?php echo $this->main ?>
              
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/sections/footer.php' ?>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/sections/foot.php' ?>
    <!-- iCheck -->
    <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/iCheck/icheck.min.js"></script>
  </body>
</html>