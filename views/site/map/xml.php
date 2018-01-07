<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>
      <loc><?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?></loc>
      <changefreq>weekly</changefreq>
   </url>
   <?php foreach ($this->sitemap as $item) { ?>
   <url>
      <loc><?php echo $GLOBALS['PROJECT']['URL']['BASE'] . '/' . \leslie::translate('contents') . '/' . \leslie::translate($item['type_plural']) . '/' . $item['permalink'] ?></loc>
      <?php $d = preg_split( "/(-|:| )/", $item['update']) ?>
      <?php if (checkdate($d[1], $d[2], $d[0])) { ?>
      <lastmod><?php echo date('Y-m-d', strtotime($item['update'])) ?></lastmod>
      <?php } ?>
   </url>
   <?php } ?>
</urlset>
