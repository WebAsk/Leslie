<!DOCTYPE html>
<!--[if IE 8]> <html class="ie ie8"> <![endif]-->
<!--[if IE 9]> <html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]> <html> <![endif]-->
   <head>
   <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'head.php'; ?>
   </head>

   <body>
      
      <nav class="navbar navbar-default container-fluid">
         <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'nav.php'; ?>
      </nav>
      
      
      <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'alert.php'; ?>


      <main class="container-fluid">
         
         <div class="col-md-8">
            <?php echo $this->main ?>
         </div>
         
         <aside class="col-md-4">
            <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'aside.php'; ?>
         </aside>
         
      </main>

      <footer class="container-fluid">
         <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
      </footer>
      
      <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'foot.php'; ?>

   </body>
</html>
