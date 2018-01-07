<?php if (!empty($this->slides)) { ?>

<div id="carousel" class="carousel slide" data-ride="carousel">
   <ol class="carousel-indicators">
      <?php for ($s = 0; $s < count($this->slides); $s++) { ?>
      <li data-target="#carousel" data-slide-to="<?php echo $s ?>"<?php if ($s == 0) { echo ' class="active"'; } ?>></li>
      <?php } ?>
   </ol>
   <div class="carousel-inner" role="listbox">
      <?php foreach ($this->slides as $key => $slide) { ?>
      <div class="item <?php if ($key == 0) { echo ' active'; } ?>">
         <img src="<?php echo $GLOBALS['PROJECT']['URL']['IMAGES'] . '/slides/' . $slide['name'] ?>" alt="<?php echo $slide['title'] ?>">
         <div class="carousel-caption">
            <h3><?php echo $slide['title'] ?></h3>
            <p><?php echo $slide['title'] ?></p>
         </div>
      </div>
      <?php } ?>
   </div>
   <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
      <span class="icon-prev" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
   </a>
   <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
      <span class="icon-next" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
   </a>
</div>

<?php } ?>