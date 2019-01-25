<?php echo $header; ?>


<?php // Breadcrumbs -------------------------------------------------- ?>

<?php $tbData->slotStart('stories/show.breadcrumbs'); ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php $tbData->slotStop(); ?>

<?php // Page title --------------------------------------------------- ?>

<?php $tbData->slotStart('stories/show.page_title'); ?>
<h1><?php echo $heading_title; ?></h1>
<?php $tbData->slotStop(); ?>

<?php // Page content ------------------------------------------------- ?>

<?php $tbData->slotStart('stories/show.page_content'); ?>

<article class="tb_article tb_thumbnail_top">
  <?php if ($image): ?>
  <div class="thumbnail">
    <span class="image-holder" style="max-width: <?php echo $image_width; ?>px;">
    <span style="padding-top: <?php echo round($image_height / $image_width, 4) * 100; ?>%;">
      <img
        <?php if (!$tbData->system['image_lazyload']): ?>
        src="<?php echo $image; ?>"
        <?php else: ?>
        src="<?php echo $tbData->theme_catalog_image_url; ?>pixel.gif"
        data-src="<?php echo $image; ?>"
        class="lazyload"
        <?php endif; ?>
        width="<?php echo $image_width; ?>"
        height="<?php echo $image_height; ?>"
        alt="<?php echo $heading_title; ?>"
        style="margin-top: -<?php echo round($image_height / $image_width, 4) * 100; ?>%" />
      />
    </span>
    </span>
  </div>
  <?php endif; ?>
  <div class="tb_meta">
    <p class="tb_date"><i class="fa fa-calendar"></i> <?php echo $date_added; ?></p><?php if ($tags): ?><p class="tb_tags">
      <i class="fa fa-tags"></i>
      <?php $i=1; foreach ($tags as $tag): ?>
      <a href="<?php echo $tag['url']; ?>"><?php echo $tag['name']; ?></a><?php if ($i < count($tags)): ?>, <?php endif; ?>
      <?php $i++; endforeach; ?>
    </p><?php endif; ?><?php if ($settings['comments'] != 'disabled'): ?><p class="tb_commens_count">
      <i class="fa fa-comments"></i>
      <?php if ($settings['comments'] == 'disqus'): ?>
      <a href="<?php echo $tbData->current_url; ?>#disqus_thread"></a>
      <?php endif; ?>
      <?php if ($settings['comments'] == 'facebook'): ?>
      <a href="<?php echo $tbData->current_url; ?>#comments"><span class="fb-comments-count" data-href="<?php echo $story['url']; ?>"></span></a>
      <?php endif; ?>
    </p>
    <?php endif; ?>
    <?php if ($settings['social_share']): ?>
    <div class="tb_social_share"><?php echo $settings['social_share']; ?></div>
    <?php endif; ?>
  </div>
  <div class="tb_text_wrap">
    <?php echo $description; ?>
  </div>

  <?php if ($settings['comments'] != 'disabled'): ?>
  <div id="comments" class="tb_comments lazyload" data-expand="100">
    <?php // FACEBOOK COMMENTS ?>
    <?php if ($settings['comments'] == 'facebook'): ?>
    <div class="fb-comments" data-href="<?php echo $tbData->current_url; ?>" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
    <script type="text/javascript">
    tbApp.onScriptLoaded(function() {
      $('#comments').bind('lazybeforeunveil', function() {
        var parseFBXML = function() {
          FB.XFBML.parse(document.getElementById('comments'));
        };
        if (window.FB_XFBML_parsed === undefined) {
          window.FB_XFBML_parsed = parseFBXML;
        } else {
          parseFBXML();
        }
      });
    });
    </script>
    <?php endif; ?>

    <?php // DISQUS COMMENTS ?>
    <?php if ($settings['comments'] == 'disqus'): ?>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
    var disqus_shortname = '<?php echo $settings["disqus_shortname"]; ?>';
    var disqus_config = function () {
      this.language = '<?php echo $tbData->language_code; ?>';
    };

    (function() {
      var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
      dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
      (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</article>
<?php $tbData->slotStop(); ?>


<?php echo $footer; ?>