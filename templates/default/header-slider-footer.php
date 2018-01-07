<!DOCTYPE html>
<html lang="<?php echo \leslie::$locale ?>">
    <head>
        <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . $this->sections['name'] . DIRECTORY_SEPARATOR . 'head.php'; ?>
    </head>

    <body itemscope itemtype="http://schema.org/WebPage">

        <header class="<?php echo $this->header['class'] ?>">
           <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . $this->sections['name'] . DIRECTORY_SEPARATOR . $this->header['name'] . '.php'; ?>
        </header>

        <div class="<?php echo $this->slider['class'] ?>">
            <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . $this->sections['name'] . DIRECTORY_SEPARATOR . $this->slider['name'] . '.php'; ?>
        </div>

        <footer class="<?php echo $this->footer['class'] ?>">
           <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . $this->sections['name'] . DIRECTORY_SEPARATOR . $this->footer['name'] . '.php'; ?>
        </footer>

        <?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . $this->sections['name'] . DIRECTORY_SEPARATOR . 'foot.php' ?>

    </body>
</html>
