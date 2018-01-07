<article>
   <header class="page-header">
   <h1><?php echo $this->title ?></h1>
   </header>

   <?php if (!empty($this->images)) { ?>
   <?php $count_images = count($this->images); ?>
   <div id="images" class="carousel slide" data-ride="carousel">
      <?php if ($count_images > 1) { ?>
      <ol class="carousel-indicators">
         <?php for ($s = 0; $s < count($this->images); $s++) { ?>
         <li data-target="#carousel" data-slide-to="<?php echo $s ?>"<?php if ($s == 0) { echo ' class="active"'; } ?>></li>
         <?php } ?>
      </ol>
      <?php } ?>
      <div class="carousel-inner" role="listbox">
         <?php foreach ($this->images as $key => $img) { ?>
         <div class="item <?php if ($key == 0) { echo ' active'; } ?>">
            <img src="<?php echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] . '/images/large/' . $img['name'] ?>" alt="<?php echo $img['title'] ?>">
         </div>
         <?php } ?>
      </div>
      <?php if ($count_images > 1) { ?>
      <a class="left carousel-control" href="#images" role="button" data-slide="prev">
         <span class="icon-prev" aria-hidden="true"></span>
         <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#images" role="button" data-slide="next">
         <span class="icon-next" aria-hidden="true"></span>
         <span class="sr-only">Next</span>
      </a>
      <?php } ?>
   </div>
    <hr>
   <?php } ?>
   
   <div id="description">
   <?php echo $this->item['description'] ?>
   </div>
   <p class="text-right">
     Pubblicato <?php echo htmlentities(strftime('%A %e %B %Y', strtotime($this->item['insert']))) ?> 
   </p>
   <p class="text-right">
     Modificato <?php echo htmlentities(strftime('%A %e %B %Y', strtotime($this->item['update']))) ?> 
   </p>
</article>

<?php if (!empty($this->items)) { ?>
<hr>

<?php $items_count = count($this->items) ?>
<?php foreach ($this->items as $key => $item) { ?>
<article class="row">
   <?php if (!empty($item['image'])) { ?>
   <div class="col-xs-12 col-sm-6 col-md-4">
      <img src="<?php  echo $GLOBALS['PROJECT']['URL']['DOCUMENTS'] ?>/images/small/<?php echo $item['image'] ?>" alt="<?php echo $item['title'] ?>" style="width: 100%">
   </div>
   <div class="col-xs-12 col-sm-6 col-md-8">
   <?php } else { ?>
   <div class="col-xs-12">
   <?php } ?>
      <h3><a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents') . '/' . $item['permalink'] ?>"><?php echo htmlentities($item['title']) ?></a></h3>
      <?php echo !empty($item['intro'])? '<p>' . htmlentities($item['intro']) . '</p>': $item['description'] ?>
   </div>
</article>
<?php if ($items_count != $key + 1) { ?>
<hr>
<?php } ?>
<?php } ?>
<?php } ?>

<?php if ($this->comments) { ?>

<hr>

<h5>Commenti</h5>
<div>
   <form action="<?php echo $this->url ?>?action=comment" method="post" id="item_comment_form"class="form-horizontal">
      <div class="form-group">
         <div class="col-xs-12 col-sm-6">
           <input type="text" name="items[items_languages][title]"<?php if (!empty($_POST['items'])) { echo ' value="' . $_POST['items']['items_languages']['title'] . '"'; } ?> required class="form-control" placeholder="<?php echo \leslie::translate('Name') ?>">
         </div>
         <div class="col-xs-12 col-sm-6">
           <input type="email" name="items[users][email]"<?php if (!empty($_POST['items'])) { echo ' value="' . $_POST['items']['users']['email'] . '"'; } ?> required class="form-control" placeholder="Email">
         </div>
      </div>
      <div class="form-group">
         <div class="col-xs-12">
            <textarea name="items[items_languages][description]" required id="items_languages_description">
               <?php if (!empty($_POST['items'])) { echo $_POST['items']['items_languages']['description']; } ?>
            </textarea>
         </div>
      </div>
      <div class="form-group">
         <div class="col-xs-12 text-right">
            <?php if ($GLOBALS['PROJECT']['CAPTCHA']['SWITCH']) { ?>
               <div id="captcha"></div>
            <?php } else { ?>
               <button type="submit" class="btn btn-primary"><?php echo leslie::translate('Post comment') ?></button>
            <?php } ?>
         </div>
      </div>
   </form>
</div>

<script>
   $(function() {
      CKEDITOR.config.toolbar = [
         ['Bold','Italic','Underline']
      ] ;
      CKEDITOR.replace( 'items_languages_description', {
         <?php if (file_exists($GLOBALS['PROJECT']['PATHS']['ROOT'] . '/public/plugins/ckeditor/config.js')) { ?>
         customConfig: '<?php echo $GLOBALS['PROJECT']['URL']['BASE'] . '/plugins/ckeditor/config.js' ?>'
         <?php } else { ?>
         width: '100%'
         <?php } ?>
      });
   });
</script>

<?php if ($GLOBALS['PROJECT']['CAPTCHA']['SWITCH']) { ?>
<script type="text/javascript">
   var captcha = function() {
      grecaptcha.render('captcha', {
         'sitekey' : '<?php echo $GLOBALS['PROJECT']['CAPTCHA']['KEY']['client']; ?>',
         callback : function(response) {
            $('#item_comment_form').validate({
               lang: 'it',
               rules: {
                  'items[items_languages][title]': {
                   required: true,
                   minlength: 4
                 },
                 'items[users][email]': {
                   required: true,
                   email: true
                 },
                 'items[items_languages][description]': {
                     required: true,
                     minlength: 4
                  }
               }
            });
            if ($('#item_comment_form').valid()) {
               $('#item_comment_form').submit();
            } else {
               grecaptcha.reset();   
            }
            
         }
     });
   };
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=captcha&render=explicit" async defer></script>
<?php } else { ?>
<script>
   $(function () {
      $('#item_comment_form').validate({
         lang: 'it',
         rules: {
            'items[items_languages][title]': {
               required: true,
               minlength: 4
            },
            'items[users][email]': {
               required: true,
               email: true
            },
            'items[items_languages][description]': {
                required: true,
                minlength: 4
             }
         }
      });
   });
</script>
<?php } ?>
<?php } ?>