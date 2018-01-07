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
      
      <div class="container-fluid">
      <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'alert.php'; ?>
      </div>

      <main class="container-fluid">
         <?php echo $this->main ?>
         
      </main>

      <footer class="container-fluid">
         <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
      </footer>

      <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'foot.php'; ?> 

   </body>
</html>
