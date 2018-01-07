<!DOCTYPE html>
<html lang="it">

   <head>
      
      <!-- Dropzone.js -->
      <link href="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/dropzone/dist/min/dropzone.min.css" rel="stylesheet">
      
      <?php include_once FRAMEWORK_PATH_TPL . '/gentellela/snippets/head.php' ?>
      
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
                   <h3>Upload multiplo di files</h3>
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
                 <div class="col-md-12 col-sm-12 col-xs-12">
                   <div class="x_panel">
                     <div class="x_title">
                       <h2>Upload files multiplo</h2>
                       <ul class="nav navbar-right panel_toolbox">
                         <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                         </li>
                         <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                           <ul class="dropdown-menu" role="menu">
                             <li><a href="#">Settings 1</a>
                             </li>
                             <li><a href="#">Settings 2</a>
                             </li>
                           </ul>
                         </li>
                         <li><a class="close-link"><i class="fa fa-close"></i></a>
                         </li>
                       </ul>
                       <div class="clearfix"></div>
                     </div>
                     <div class="x_content">
                       <p>Trascina pi&ugrave; files nel contenitore qua sotto oppure cliccaci per selezionarli.</p>
                       <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" class="dropzone">
                       </form>
                       <br />
                       <br />
                       <br />
                       <br />
                     </div>
                   </div>
                 </div>
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

      <!-- Dropzone.js -->
      <script src="<?php echo FRAMEWORK_URL_TPL ?>/gentellela/vendors/dropzone/dist/min/dropzone.min.js"></script>
      
   </body>
</html>