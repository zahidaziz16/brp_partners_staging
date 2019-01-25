<?php if(!empty($menu_banner)): ?>
<div
  class="
    tb_menu_banner
    <?php if ($settings['menu_banner_position'] == 0 || $settings['menu_banner_position'] == 1): ?>
    col
    col-xs-12
    col-sm-1-5
    pos-xs-<?php echo $settings['menu_banner_position']; ?>
    pos-sm-<?php echo $settings['menu_banner_position']; ?>
    pos-md-<?php echo $settings['menu_banner_position']; ?>
    pos-lg-<?php echo $settings['menu_banner_position']; ?>
    <?php endif; ?>
    col-align-<?php echo $settings['menu_banner_align']; ?>
    col-valign-<?php echo $settings['menu_banner_valign']; ?>
  "
  style="
    <?php if(!empty($settings['menu_banner_size']) && ($settings['menu_banner_position'] == 0 || $settings['menu_banner_position'] == 1)): ?>
    width: <?php echo $settings['menu_banner_size']; ?>%;
    max-width: none;
    <?php endif; ?>
    <?php if(strlen($settings['menu_banner_padding'])): ?>
    padding: <?php echo $settings['menu_banner_padding']; ?>px;
    <?php endif; ?>
    <?php if(!empty($settings['menu_banner_bg'])): ?>
    background-color: <?php echo $settings['menu_banner_bg']; ?>;
    <?php endif; ?>
  "
>
  <?php if(!empty($settings['menu_banner_url'])): ?>
  <a href="<?php echo $settings['menu_banner_url']; ?>" target="<?php echo $settings['menu_banner_url_target']; ?>"></a>
  <?php endif; ?>
  <img
    src="<?php echo $menu_banner; ?>"
    width="<?php echo $settings['menu_banner_width']; ?>"
    height="<?php echo $settings['menu_banner_height']; ?>"
    <?php if (!empty($settings['menu_banner_inline_styles'])): ?>
    style="<?php echo $settings['menu_banner_inline_styles']; ?>"
    <?php endif; ?>
    alt=""
  />
</div>
<?php endif; ?>
