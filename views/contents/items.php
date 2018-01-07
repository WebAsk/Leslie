<section>
   <h1><?php echo $this->title ?></h1>
   <?php if (!empty($this->item['description'])) { ?>
   <hr>
   <p><?php echo $this->item['description'] ?></p>
   <?php } ?>
   <hr>

   <?php $items_count = count($this->items) ?>
   <?php foreach ($this->items as $key => $item) { ?>
   <?php $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents') . '/' . $item['permalink'] ?>
   <article class="row" itemprop="<?php echo $item['type_singular'] ?>" itemscope itemtype="http://schema.org/<?php echo ucfirst($item['type_singular']) ?>">
      <div class="col-xs-12 col-sm-6 col-md-4">
         <a href="<?php echo $href ?>">
            <img src="<?php if (!empty($item['image'])) { echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] ?>/images/small/<?php echo $item['image']; } else { echo 'http://placehold.it/300x200?text=no+image'; } ?>" alt="<?php echo htmlentities($item['title']) ?>" itemprop="image" style="width: 100%">
         </a>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-8">
         <h3 class="md-mt-0" itemprop="name"><a href="<?php echo $href ?>" itemprop="url"><?php echo htmlentities($item['title']) ?></a></h3>
         <p itemprop="description"><?php echo htmlentities($item['intro']) ?></p>
      </div>
   </article>
   <?php if ($items_count != $key + 1) { ?>
   <hr>
   <?php } ?>
   <?php } ?>
</section>