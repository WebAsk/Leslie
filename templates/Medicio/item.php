<!DOCTYPE html>
<html lang="it">

<head>
<?php include_once 'blocks' . DIRECTORY_SEPARATOR . 'head.php'; ?>
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-custom">

<div id="wrapper">
   
   <?php include_once 'blocks' . DIRECTORY_SEPARATOR . 'nav.php'; ?>
	
   <?php echo $this->main ?>
   
   <?php include_once 'blocks' . DIRECTORY_SEPARATOR . 'footer.php'; ?>

</div>
   
<a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>

<?php include_once 'blocks' . DIRECTORY_SEPARATOR . 'foot.php'; ?>

</body>

</html>
