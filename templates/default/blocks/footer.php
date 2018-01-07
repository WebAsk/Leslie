
<div class="row">
   <div class="col-xs-12">
      <?php echo $GLOBALS['COMPANY']['NAME'] . ' | <a href="#" onclick="this.setAttribute(\'href\', \'mailto:' . \functions::js_split_contact($GLOBALS['COMPANY']['EMAIL']). '\')">' . $GLOBALS['COMPANY']['EMAIL'] . '</a>';
      if (isset($GLOBALS['COMPANY']['TEL'])) {
        echo ' | <a href="#" onclick="this.setAttribute(\'href\', \'tel:' . \functions::js_split_contact($GLOBALS['COMPANY']['TEL']). '\')">' . \functions::antispam_contact($GLOBALS['COMPANY']['TEL']) . '</a>';
      }
      if (isset($GLOBALS['COMPANY']['VAT'])) {
        echo ' | P. IVA: ' . $GLOBALS['COMPANY']['VAT']['NUMBER'];
      }
      echo ' | <a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '/info/privacy">Privacy</a>';
      echo ' | <a href="' . $GLOBALS['PROJECT']['URL']['BASE'] . '/info/cookie">Cookie</a>';
      ?> | <a href="http://www.webask.it" target="_blank">WebAsk</a>
   </div>
</div>