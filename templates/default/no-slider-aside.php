<!DOCTYPE html>
<html>
   <head>
   <?php include_once 'templates/blocks/head.php'; ?>
   </head>

   <body>

      <header class="container">
         <?php include_once 'templates/blocks/header.php'; ?>
      </header>
      
      <nav id="nav" class="container">
         <?php include_once 'templates/blocks/nav.php'; ?>
      </nav>
      
      <main class="container">
         
         <?php echo $this->main ?>
         
      </main>

      <footer class="container">
         <?php include_once 'templates/blocks/footer.php'; ?>
      </footer>

      <?php foreach (Config::$default_js as $js_path) { ?>
      <script type="text/javascript" src="<?php echo URL . $js_path ?>"></script>
      <?php } ?>

   </body>
</html>
