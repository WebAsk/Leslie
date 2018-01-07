<meta charset="utf-8">

<title><?php echo $this->title ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo $this->description ?>">
<meta name="author" content="<?php echo $this->author ?>">

<meta property="og:url" content="<?php echo $this->url ?>" />
<!--<meta property="og:type" content="article" />-->
<meta property="og:title" content="<?php echo $this->title ?>" />
<meta property="og:description" content="<?php echo $this->description ?>" />
<meta property="og:image" content="<?php echo $this->image ?>" />
<meta property="og:image:width" content="<?php echo $this->image_width ?>" />
<meta property="og:image:height" content="<?php echo $this->image_height ?>" />
<meta property="og:site_name" content="<?php echo $GLOBALS['PROJECT']['NAME'] ?>" />
<!--
<meta property="article:published_time" content="2016-10-26T08:30:00+01:00" />
<meta property="article:modified_time" content="2016-10-26T08:37:41+01:00" />
<meta property="article:section" content="" />
<meta property="article:tag" content="" />
<meta property="fb:admins" content="" /> 
<meta property="fb:app_id" content="" />
<meta property="og:locale" content="it_IT" />
<meta property="og:locale:alternate" content="en_US" />
-->
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $GLOBALS['PROJECT']['URL']['IMAGES']?>/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" href="<?php echo $GLOBALS['PROJECT']['URL']['IMAGES']?>/favicons/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo $GLOBALS['PROJECT']['URL']['IMAGES']?>/favicons/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="<?php echo $GLOBALS['PROJECT']['URL']['IMAGES']?>/favicons/manifest.json">
<link rel="mask-icon" href="<?php echo $GLOBALS['PROJECT']['URL']['IMAGES']?>/favicons/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">

<!-- css -->
<link href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/plugins/cubeportfolio/css/cubeportfolio.min.css">
<link href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/css/nivo-lightbox.css" rel="stylesheet" />
<link href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/css/nivo-lightbox-theme/default/default.css" rel="stylesheet" type="text/css" />
<link href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/css/owl.carousel.css" rel="stylesheet" media="screen" />
<link href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/css/owl.theme.css" rel="stylesheet" media="screen" />
<link href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/css/animate.css" rel="stylesheet" />
<link href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/css/style.css" rel="stylesheet">

<!-- boxed bg -->
<link id="bodybg" href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/bodybg/bg1.css" rel="stylesheet" type="text/css" />
<!-- template skin -->
<link id="t-colors" href="<?php echo FRAMEWORK_URL_TPL ?>/Medicio/color/default.css" rel="stylesheet">

<?php echo $this->getStyles(); ?>