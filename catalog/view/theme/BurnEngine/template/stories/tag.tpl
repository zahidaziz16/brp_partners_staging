<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('stories/tag.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('stories/tag.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Tag description ---------------------------------------------- ?>

<?php $tbData->slotStart('stories/tag.tag_description'); ?>
<div class="tb_text_wrap">
  <?php echo $description; ?>
</div>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('stories/tag.page_content'); ?>
<?php if (isset($tbData['system.page_content']['listing_classes'])) {
  $listing_classes  = $tbData['system.page_content']['listing_classes'];
  $listing_classes .= $tbData['system.page_content']['view_mode'] != 'grid' ? ' tb_thumbnail_' . $settings['thumbnail_position'] : '';
} else {
  $listing_classes  = ' tb_list_view tb_style_bordered';
  $listing_classes .= ' tb_thumbnail_' . $settings['thumbnail_position'];
}
?>
<div class="tb_articles tb_listing <?php echo $listing_classes; ?>">
  <?php foreach ($stories as $story): ?>
  <div class="tb_article tb_item">
    <h2><a href="<?php echo $story['url']; ?>"><?php echo $story['title']; ?></a></h2>
    <div class="tb_meta">
      <p class="tb_date"><i class="fa fa-calendar"></i> <?php echo $story['date_added']; ?></p><?php if ($story['tags']): ?><p class="tb_tags">
        <i class="fa fa-tags"></i>
        <?php $i=1; foreach ($story['tags'] as $tag): ?>
        <a href="<?php echo $tag['url']; ?>"><?php echo $tag['name']; ?></a><?php if ($i < count($story['tags'])): ?>, <?php endif; ?>
        <?php $i++; endforeach; ?>
      </p><?php endif; ?><?php if ($settings['comments'] != 'disabled'): ?><p class="tb_commens_count">
        <i class="fa fa-comments"></i>
        <?php if ($settings['comments'] == 'disqus'): ?>
        <a href="<?php echo $story['url']; ?>#disqus_thread"></a>
        <?php endif; ?>
        <?php if ($settings['comments'] == 'facebook'): ?>
        <a href="<?php echo $story['url']; ?>#comments"><span class="fb-comments-count" data-href="<?php echo $story['url']; ?>"></span></a>
        <?php endif; ?>
      </p>
      <?php endif; ?>
    </div>
    <?php if ($story['thumb']): ?>
    <a class="thumbnail" href="<?php echo $story['url']; ?>">
      <span class="image-holder" style="max-width: <?php echo $story['thumb_width']; ?>px;">
      <span style="padding-top: <?php echo round($story['thumb_height'] / $story['thumb_width'], 4) * 100; ?>%">
        <img
          <?php if (!$tbData->system['image_lazyload']): ?>
          src="<?php echo $story['thumb']; ?>"
          <?php else: ?>
          src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif"
          data-src="<?php echo $story['thumb']; ?>"
          class="lazyload"
          <?php endif; ?>
          width="<?php echo $story['thumb_width']; ?>"
          height="<?php echo $story['thumb_height']; ?>"
          alt="<?php echo $story['title']; ?>"
          style="margin-top: -<?php echo round($story['thumb_height'] / $story['thumb_width'], 4) * 100; ?>%" />
        />
      </span>
      </span>
    </a>
    <?php endif; ?>
    <div class="tb_description tb_text_wrap">
      <?php echo $story['description']; ?>
      <a class="tb_read_more tb_main_color" href="<?php echo $story['url']; ?>"><?php echo $text_read_more; ?> <span>&rsaquo;</span></a>
    </div>
  </div>
  <?php endforeach; ?>

  <?php if (!$stories): ?>
  <p class="tb_empty"><?php echo $text_no_articles; ?></p>
  <?php endif; ?>
</div>

<?php if ($stories): ?>
<div class="pagination">
  <?php echo str_replace('pagination', 'links', $pagination); ?>
  <?php if (!empty($results)): ?>
  <div class="results"><?php echo $results; ?></div>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php if (!empty($tbData['system.page_content']['view_mode']) && $tbData['system.page_content']['view_mode'] == 'grid'): ?>
<script type="text/javascript" data-critical="1">
adjustItemSize('#{{widget_dom_id}}', <?php echo $tbData['system.page_content']['restrictions_json']; ?>);
</script>
<?php endif; ?>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>