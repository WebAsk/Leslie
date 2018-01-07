<!DOCTYPE html>
<html>
   <head>
   <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'head.php'; ?>
   </head>

   <body>
      
      <?php echo $this->main ?>

      <?php echo $this->getScripts(); ?>      

   </body>
</html>
