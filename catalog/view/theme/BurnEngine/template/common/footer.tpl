<?php if (!isset($tbData)) require DIR_APPLICATION . '/view/theme/BurnEngine/template/tb/install_error.tpl'; ?>
<!-- BEGIN_COMMON_FOOTER -->
<?php if ($tbData->display_footer): ?>
<footer id="bottom" class="tb_area_bottom <?php echo $tbData->bottom_css_classes; ?><?php if($tbData->system['bg_lazyload']) echo ' lazyload" data-expand="' . $tbData->system['bg_lazyload_expand']; ?>">
  <div class="row tb_gut_xs_20 tb_gut_sm_20 tb_gut_md_30">
    <div class="col-md-auto col-sm-auto col-xs-12 col-valign-middle">
      <p id="copy"><span><?php echo $tbData->theme_config['copyright']; ?></p>
    </div>
    <?php if ($tbData->payment_images): ?>
    <div class="col-md-fill col-sm-fill col-xs-12 col-valign-middle">
      <div id="payment_images">
        <?php foreach($tbData->payment_images['rows'] as $payment): ?>
        <div class="tb_payment">
          <?php if ($payment['type'] == 'image'): ?>
          <?php if ($payment['link_url']): ?>
          <a href="<?php echo $payment['link_url'];?>" target="<?php echo $payment['link_target'];?>">
            <img src="<?php echo $payment['http_file']; ?>"<?php if($payment['width']): ?> width="<?php echo $payment['width']; ?>" height="<?php echo $payment['height']; ?>"<?php endif; ?> alt="" />
          </a>
          <?php else: ?>
          <img src="<?php echo $payment['http_file']; ?>"<?php if($payment['width']): ?> width="<?php echo $payment['width']; ?>" height="<?php echo $payment['height']; ?>"<?php endif; ?> alt="" />
          <?php endif; ?>
          <?php else: ?>
          <?php echo html_entity_decode($payment['seal_code'], ENT_COMPAT, 'UTF-8'); ?>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</footer>
<?php endif; ?>
</div>

<?php // Quickview ?>
<?php if ($tbData->is_quickview): ?>
<script data-capture="0">
var tb_iframe_height=document.getElementById('wrapper').offsetHeight;window.parent.tbShowQuickView&&window.parent.tbShowQuickView(tb_iframe_height);window.addEventListener('load',function(){var tb_iframe_height=document.getElementById('wrapper').offsetHeight;window.parent.tbResizeQuickView&&window.parent.tbResizeQuickView(tb_iframe_height);});
</script>
<?php endif; ?>

<?php // Critical CSS ?>
<?php if (!empty($tbData->system['critical_css'])): ?>
<script type="text/javascript" data-capture="0">
function loadCss(e){var t=document.createElement("link");t.rel="stylesheet",t.href=e,document.getElementsByTagName("head")[0].appendChild(t)}<!--{{stylesheets_placeholder}}-->
</script>
<?php endif; ?>

<?php // Critical JS ?>
<?php if ($tbData->optimize_js_load): ?>
<!--{{javascript_resources_placeholder_footer}}-->
<?php endif; ?>
<?php if (!$tbData->is_quickview): ?>
<script type="text/javascript" data-critical="1">
if (typeof window.tb_wishlist_label != 'undefined') {
    Array.prototype.forEach.call(document.querySelectorAll('a.wishlist_total, li.wishlist_total > a > .tb_text'), function(el) {
        var holder = document.createElement('span'),
            number = document.createTextNode(window.tb_wishlist_label.replace(/[^0-9]/g, ''));

        holder.appendChild(number);
        holder.classList.add('tb_items');
        el.appendChild(holder);
    });
}
</script>
<?php endif; ?>
<?php if (!empty($tbData->system['critical_css'])): ?>
<script type="text/javascript" data-capture="0">
setTimeout(function() {
  [].forEach.call(document.querySelectorAll('.tb_preload'), function(el) {
    el.className = el.className.replace(/\btb_loading\b/, '');
  });
}, 100);
</script>
<?php endif; ?>

<?php // Custom JS ?>
<?php if ($tbData->common['custom_javascript']): ?>
<script>
  <?php echo $tbData->common['custom_javascript']; ?>
</script>
<?php endif; ?>

<?php // Sticky header ?>
<?php if ($tbData->style['sticky_header']): ?>
<script>
sticky_header (
  "<?php echo $tbData->style['sticky_header_style']; ?>",
  "<?php echo $tbData->style['sticky_header_layout']; ?>",
  "<?php echo $tbData->style['sticky_header_padding']; ?>"
);
</script>
<?php endif; ?>

<?php // Cookie policy ?>
<?php if ($tbData->common['cookie_policy']): ?>
<script>
cookie_policy('<?php echo $tbData->text_cookie_policy_text; ?>');
</script>
<?php endif; ?>

<?php // Scroll to top ?>
<?php if ($tbData->common['scroll_to_top']): ?>
<script>
scroll_to_top ();
</script>
<?php endif; ?>

</body>
</html>
