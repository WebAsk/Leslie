<!DOCTYPE html>
<!--[if IE 8]> <html class="ie ie8"> <![endif]-->
<!--[if IE 9]> <html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]> <html> <![endif]-->
   <head>
   <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'head.php'; ?>
   </head>

   <body>

      <main>
         <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'alerts.php'; ?>

      
         <?php include_once $this->view ?>
         
      </main>

      <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'foot.php'; ?>    

   </body>
</html>
