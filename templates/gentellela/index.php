<!DOCTYPE html>
<html lang="it">
   <head>
      <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/snippets/head.php' ?>
      <!-- iCheck -->
      <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
      <!-- bootstrap-progressbar -->
      <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
      <!-- JQVMap -->
      <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
      <!-- bootstrap-daterangepicker -->
      <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
   </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
         <div class="col-md-3 left_col">
            <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/snippets/sidebar.php' ?>
         </div>

         <!-- top navigation -->
         <div class="top_nav">
            <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/snippets/topnav.php' ?>
         </div>
         <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <?php echo $this->main ?>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
           <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/snippets/footer.php' ?>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

     
      <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/snippets/foot.php' ?>
     
   </body>
</html>
