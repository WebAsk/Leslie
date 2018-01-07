<!DOCTYPE html>
<html lang="it">

<head>
<?php include_once 'blocks' . DIRECTORY_SEPARATOR . 'head.php'; ?>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-custom">

<div id="wrapper">
   
   <?php include_once 'blocks' . DIRECTORY_SEPARATOR . 'nav.php'; ?>
   
   <section class="home-section paddingbot-60">	
      <div class="container">
         <div class="row">
            <div class="col-md-12">
	
               <?php echo $this->main ?>
               
            </div>
         </div>
      </div>
   </section>
   
   <?php include_once 'blocks' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

</div>
   
<a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>

<?php include_once 'blocks' . DIRECTORY_SEPARATOR . 'foot.php'; ?>

</body>

</html>
