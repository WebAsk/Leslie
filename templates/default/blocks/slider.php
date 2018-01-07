<?php if (!empty($this->slides)) { ?>
    <?php $count_slides = count($this->slides); ?>
    <div id="slides" class="carousel slide row" data-ride="carousel">
        <?php if ($count_slides > 1) { ?>
        <ol class="carousel-indicators">
            <?php for ($s = 0; $s < count($this->slides); $s++) { ?>
                <li data-target="#carousel" data-slide-to="<?php echo $s ?>"<?php if ($s == 0) { echo ' class="active"'; } ?>></li>
            <?php } ?>
        </ol>
        <?php } ?>
        <div class="carousel-inner" role="listbox">
            <?php foreach ($this->slides as $key => $slide) { ?>
            <div class="item <?php if ($key == 0) { echo ' active'; } ?>">
                <img src="<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/slides/'; if (!empty($slide['folder'])) { echo $slide['folder'] . '/'; } echo $slide['name'] ?>" alt="<?php echo $slide['title'] ?>">
                <div class="carousel-caption">
                    <h3><span><?php if (!empty($slide['icon'])) { echo '<i class="' . $slide['icon'] . '"></i> '; } echo $slide['title'] ?></span></h3>
                    <p><?php echo $slide['description'] ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php if ($count_slides > 1) { ?>
        <a class="left carousel-control" href="#slides" role="button" data-slide="prev">
            <span class="icon-prev" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#slides" role="button" data-slide="next">
            <span class="icon-next" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        <?php } ?>
    </div>
<?php } ?>