<?php if (!empty($this->popular_contents[0])) { ?>
<div class="row">
   <div class="col-xs-12 aside-title">
      <?php echo \leslie::translate('Most read content') ?>
   </div>
   <article class="col-xs-12">
      <?php $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents') . '/' . $this->popular_contents[0]['permalink'] ?>
      <?php $title = htmlentities($this->popular_contents[0]['title']) ?>
      <?php if (!empty($this->popular_contents[0]['image'])) { ?>
      <a href="<?php echo $href ?>">
         <img src="<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] ?>/images/small/<?php echo $this->popular_contents[0]['image'] ?>" alt="<?php echo $title ?>" style="width: 100%">
      </a>
      <?php } ?>
      <h4><a href="<?php echo $href ?>"><?php echo $title ?></a></h4>
      <p><?php echo htmlentities($this->popular_contents[0]['intro']) ?></p>
   </article>
</div>
<?php } ?>

<?php if (!empty($this->last_contents[0])) { ?>
<div class="row">
   <div class="col-xs-12 aside-title">
      <?php echo \leslie::translate('Last content') ?>
   </div>
   <article class="col-xs-12">
      <?php $href = $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents') . '/' . $this->last_contents[0]['permalink'] ?>
      <?php $title = htmlentities($this->last_contents[0]['title']) ?>
      <?php if (!empty($this->last_contents[0]['image'])) { ?>
      <a href="<?php echo $href ?>">
         <img src="<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] ?>/images/small/<?php echo $this->last_contents[0]['image'] ?>" alt="<?php echo $title ?>" style="width: 100%">
      </a>
      <?php } ?>
      <h4><a href="<?php echo $href ?>"><?php echo $title ?></a></h4>
      <p><?php echo htmlentities($this->last_contents[0]['intro']) ?></p>
   </article>
</div>
<?php } ?>

<hr>

<?php if (!empty($this->joints)) { ?>

    <?php $keys = array_keys($this->joints); $last = array_pop($keys) ?>
    <?php foreach($this->joints as $key => $joints) { ?>
    <div class="row">
        <div class="col-xs-12">
            <?php $translated_joint_name = leslie::translate($key) ?>
            <?php if (empty($joints['multiple'])) { ?>
            <div class="aside-title"><?php echo ucfirst($translated_joint_name) ?></div>
            <ul>
               <?php foreach($joints['items'] as $joint) { ?>
               <li><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] . '/' . leslie::translate('contents') . '/' . $joint['permalink'] ?>"><?php echo $joint['title'] ?></a></li>
               <?php } ?>
            </ul>
            <?php } else { ?>
            <div class="aside-title"><?php echo ucfirst($translated_joint_name) ?></div>
            <?php foreach($joints['items'] as $joint) { ?>
            <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] . '/' . leslie::translate('contents') . '/' . $joint['permalink'] ?>" class="label label-primary" style="display: inline-block; margin: 0 2px 2px 0; line-height: 1.4em"><?php echo $joint['title'] ?></a>&nbsp;
            <?php } ?>
        </div>
     </div>   
<?php } ?>
<?php if ($key != $last) { ?>
<hr>
<?php } ?>
<?php } ?>
<?php } ?>

<hr>

<div class="row">
   <div class="col-xs-12">
      <div class="aside-title"><?php echo \leslie::translate('Share') ?></div>
      <!-- AddToAny BEGIN -->
      <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
      <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
      <a class="a2a_button_facebook"></a>
      <a class="a2a_button_twitter"></a>
      <a class="a2a_button_google_plus"></a>
      </div>
      <script async src="https://static.addtoany.com/menu/page.js"></script>
      <!-- AddToAny END -->
   </div>
</div>