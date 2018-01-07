<section>
   <header class="page-header">
      <h2><?php echo \leslie::translate('Most read contents') ?></h2>
   </header>
   <div class="row">
   <?php foreach ($this->popular_contents as $content) { ?>

      <article class="col-xs-12 col-sm-6 col-md-4 col-lg-3" itemprop="<?php echo $content['singular'] ?>" itemscope itemtype="http://schema.org/<?php echo ucfirst($content['singular']) ?>">
         <?php $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents') . '/' . $content['permalink'] ?>
         <?php if (!empty($content['image'])) { ?>
         <a href="<?php echo $href ?>">
            <img src="<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] ?>/images/small/<?php echo $content['image'] ?>" itemprop="image" alt="<?php echo $content['title'] ?>" style="width: 100%">
         </a>
         <?php } ?>
         <h3 itemprop="name"><a href="<?php echo $href ?>" itemprop="url"><?php echo htmlentities($content['title']) ?></a></h3>
         <p itemprop="description"><?php echo htmlentities($content['intro']) ?></p>
      </article>

   <?php } ?>
   </div>
</section>
<section>
   <header class="page-header">
      <h2><?php echo \leslie::translate('Last published contents') ?></h2>
   </header>
   <div class="row">
   <?php foreach ($this->last_contents as $content) { ?>

      <article class="col-xs-12 col-sm-6 col-md-4 col-lg-3" itemprop="<?php echo $content['singular'] ?>" itemscope itemtype="http://schema.org/<?php echo ucfirst($content['singular']) ?>">
         <?php $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents') . '/' . $content['permalink'] ?>
         
         <?php if (!empty($content['image'])) { ?>
         <a href="<?php echo $href ?>">
            <img src="<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] ?>/images/small/<?php echo $content['image'] ?>" itemprop="image" alt="<?php echo $content['title'] ?>" style="width: 100%">
         </a>
         <?php } ?>
         <h3 itemprop="name"><a href="<?php echo $href ?>" itemprop="url"><?php echo htmlentities($content['title']) ?></a></h3>
         <p itemprop="description"><?php echo htmlentities($content['intro']) ?></p>
      </article>

   <?php } ?>
   </div>
</section>