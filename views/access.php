<h1 style="margin: 0 0 30px 0">
    <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>" style="text-decoration: none; color: inherit; font-weight: bold">
        <?php if (!empty($GLOBALS['PROJECT']['LOGO'])) { ?>
        <img src="<?php echo $GLOBALS['PROJECT']['LOGO'] ?>" alt="<?php echo $GLOBALS['PROJECT']['NAME'] ?>" style="height: 1em; vertical-align: top">
        <?php } ?>
        <?php echo $GLOBALS['PROJECT']['NAME'] ?>
    </a>
</h1>

<p><?php echo $this->description ?></p>

<form action="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/access" method="post" id="signin" role="form">
    <input type="hidden" name="action" value="<?php echo $this->action ?>">
    <input type="hidden" name="refresh" value="1">
    <fieldset class="form-group">
    <input type="email" name="items[users][email]" class="form-control" placeholder="Email" required autofocus>
    </fieldset>
    <fieldset class="form-group" id="signin-password">
    <div class="input-group">
    <input type="password" name="items[users][password]" class="form-control" placeholder="Password" required aria-describedby="basic-addon2">
    <a href="javascript:reset()" class="input-group-addon" id="basic-addon2">reset</a>
    </div>
    </fieldset>
    <fieldset class="form-group">
    <?php if ($GLOBALS['PROJECT']['CAPTCHA']['SWITCH']) { ?>
       <div id="captcha"></div>
    <?php } else { ?>
       <button type="submit" class="btn btn-block btn-lg btn-primary"><?php echo ucfirst(leslie::translate($this->action)) ?></button>
    <?php } ?>
    </fieldset>
</form>

<?php include_once FRAMEWORK_PATH_TPL . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . 'alert.php'; ?>

<h2><img src="<?php echo FRAMEWORK_LOGO ?>" style="height: 1em; vertical-align: bottom"> <?php echo FRAMEWORK_NAME ?> Framework</h2>
<p>
   &copy; copyright 2016 all rights reserved <a href="http://www.<?php echo FRAMEWORK_COMPANY_DOMAIN ?>"><?php echo FRAMEWORK_COMPANY_NAME ?></a>.<br>
   Stable version release: <?php echo $GLOBALS['FRAMEWORK']['VERSION'] ?>.
</p>


<?php $this->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/jquery.validate.min.js' ?>
<?php $this->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/localization/messages_it.min.js' ?>
<?php $this->scripts[] = FRAMEWORK_URL_PLUG . '/jquery/validation/additional-methods.min.js' ?>

<script type="text/javascript">
    
$(function () {

    $('#signin').validate({
        lang: 'it',
        rules: {
            'items[users][email]': {
              required: true,
              email: true
            },
            'items[users][password]': {
                required: true,
                minlength: 4
             }
        }
    });

});

function reset() {
    
    $('#signin-password').remove();
    $('input[name="action"]').val('recovery');
    $('button[type="submit"]').text('recovery');
    
}
    
</script>

<?php if ($GLOBALS['PROJECT']['CAPTCHA']['SWITCH']) { ?>

<script type="text/javascript">
    
    var captcha = function() {
        grecaptcha.render('captcha', {
           'sitekey' : '<?php echo $GLOBALS['PROJECT']['CAPTCHA']['KEY']['client'] ?>',
           
            'callback' : function(response) {
                
                if (response === $('#g-recaptcha-response').val()) {
                    
                    if ($('#signin').valid()) {
                        
                        $('#signin').submit();
                        
                    } else {
                        
                        grecaptcha.reset(); 
                        
                    }
                }
                
            }
        });
    };
   
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=captcha&render=explicit" async defer></script>

<?php } ?>

