<footer>
   
   <div class="container">
      <div class="row">
         <div class="col-sm-6 col-md-4">
            <div class="wow fadeInDown" data-wow-delay="0.1s">
            <div class="widget">
               <h5><?php echo $GLOBALS['PROJECT']['NAME'] ?></h5>
               <p>
               <?php echo $GLOBALS['PROJECT']['DESCRIPTION'] ?>
               </p>
            </div>
            </div>
         </div>
         <div class="col-sm-6 col-md-4">
            <div class="wow fadeInDown" data-wow-delay="0.1s">
            <div class="widget">
               <h5>Contatti</h5>
               <ul>
                  <li>
                     <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-fax fa-stack-1x fa-inverse"></i>
                     </span> <a href="mailto:<?php echo $GLOBALS['COMPANY']['TEL'] ?>"><?php echo $GLOBALS['COMPANY']['TEL'] ?></a>
                  </li>
                  <li>
                     <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-envelope-o fa-stack-1x fa-inverse"></i>
                     </span> <a href="mailto:<?php echo $GLOBALS['COMPANY']['EMAIL'] ?>"><?php echo $GLOBALS['COMPANY']['EMAIL'] ?></a>
                  </li>

               </ul>
            </div>
            </div>
         </div>
         <div class="col-sm-6 col-md-4">
            <div class="wow fadeInDown" data-wow-delay="0.1s">
            <div class="widget">
               <h5>Social Network</h5>
               <ul class="company-social">
                  <?php foreach ($GLOBALS['BUSINESS']['SOCIAL'] as $name => $link) { ?>
                     <li class="social-facebook"><a href="<?php echo $link ?>"><i class="fa fa-<?php echo $name ?>"></i></a></li>
                  <?php } ?>
               </ul>
            </div>
            </div>
         </div>
      </div>	
   </div>
   <div class="sub-footer">
      <div class="container">
         <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6">
               <div class="wow fadeInLeft" data-wow-delay="0.1s">
               <div class="text-left">
                  <p>&copy; Copyright <?php echo date('Y') ?> - <?php echo $GLOBALS['COMPANY']['NAME'] ?>. <?php echo leslie::translate('All rights reserved') ?>. <?php echo leslie::translate('Web site powered by') ?> <a href="http://www.webask.it" target="_blank">WebAsk</a>.</p>
               </div>
               </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
               <div class="wow fadeInRight" data-wow-delay="0.1s">
               <div class="text-right">
                  <p>P. IVA: <?php echo $GLOBALS['COMPANY']['VAT'] ?></p>
               </div>
                    <!-- 
                        All links in the footer should remain intact. 
                        Licenseing information is available at: http://bootstraptaste.com/license/
                        You can buy this theme without footer links online at: http://bootstraptaste.com/buy/?theme=Medicio
                    -->
               </div>
            </div>
         </div>	
      </div>
   </div>
</footer>

