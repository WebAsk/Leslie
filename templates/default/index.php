<!DOCTYPE html>
<html lang="it">
    <head>
        <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'head.php'; ?>
    </head>

    <body itemscope itemtype="http://schema.org/WebPage">

        <header class="container xs-pt-20 xs-pb-20">
           <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'header.php'; ?>
        </header>

        <nav class="navbar navbar-default container">
           <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'nav.php'; ?>
        </nav>

        <div class="container">
        <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'slider.php'; ?>
        </div>

        <div class="container">
        <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'alert.php'; ?>
        </div>

        <main class="container">

              <?php echo $this->main ?>

        </main>

        <footer class="container xs-pt-20 xs-pb-20 xs-mt-20">
           <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
        </footer>

        <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'foot.php' ?>

    </body>
</html>
