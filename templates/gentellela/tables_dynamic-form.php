<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/snippets/head.php' ?>
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
          <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/snippets/sidebar.php' ?>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/snippets/topnav.php' ?>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo ucfirst($this->title) ?></h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
               
               <?php echo $this->main ?>
              
            </div>
          </div>
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
   <!-- iCheck -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/iCheck/icheck.min.js"></script>
   <!-- Datatables -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/jszip/dist/jszip.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/pdfmake/build/pdfmake.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/pdfmake/build/vfs_fonts.js"></script>

   <!-- bootstrap-progressbar -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
   <!-- iCheck -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/iCheck/icheck.min.js"></script>
   <!-- bootstrap-daterangepicker -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/moment/min/moment.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
   <!-- bootstrap-wysiwyg -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/google-code-prettify/src/prettify.js"></script>
   <!-- jQuery Tags Input -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
   <!-- Switchery -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/switchery/dist/switchery.min.js"></script>
   <!-- Select2 -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/select2/dist/js/select2.full.min.js"></script>
   <!-- Parsley -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/parsleyjs/dist/parsley.min.js"></script>
   <!-- Autosize -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/autosize/dist/autosize.min.js"></script>
   <!-- jQuery autocomplete -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
   <!-- starrr -->
   <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/starrr/dist/starrr.js"></script>
  </body>
</html>