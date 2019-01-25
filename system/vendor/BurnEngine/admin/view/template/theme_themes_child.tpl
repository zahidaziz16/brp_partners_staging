<h2 class="breadcrumbs">
  <?php if ($is_bundle): ?>
  <a href="<?php echo $tbUrl->generate('themes/themes'); ?>" class="tbListAllThemes">Themes</a>
  <i class="fa fa-caret-right"></i>
  <?php endif; ?>
  <?php echo $title; ?>
</h2>

<div class="tb_listing tb_grid_view tb_size_3 tb_gut_30">
  <?php foreach ($themes as $theme): ?>
  <div class="tb_item_wrap">
    <div class="tb_item tb_theme">
      <div class="tb_thumb">
        <img src="data:image/png;base64, <?php echo $theme['preview']; ?>" />
        <div class="tb_actions align_right">
          <?php if ($theme['applied']): ?>
          <a class="s_button s_white s_h_30 s_icon_10 s_restore_10 tbResetTheme" href="<?php echo $tbUrl->generate('themes/applyTheme', '&theme_id=' . $theme['id']); ?>">Restore Defaults</a>
          <?php endif; ?>
          <?php if (!empty($theme['children'])): ?>
          <a class="s_button s_white s_h_30 s_icon_10 s_box_10 tbChildThemes" href="<?php echo $tbUrl->generate('themes/themes', '&theme_id=' . $theme['parent_id']); ?>">Theme Bundle</a>
          <?php elseif (!$theme['applied']): ?>
          <a class="s_button s_white s_h_30 s_icon_10 s_tick_10 tbApplyTheme" href="<?php echo $tbUrl->generate('themes/applyTheme', '&theme_id=' . $theme['id']); ?>">Apply Theme</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="tb_item_info">
        <h3>
          <?php echo $theme['name']; ?>
          <?php if ($theme['applied']): ?>
          <span class="tb_label_active"><i class="fa fa-check-circle"></i> Active</span>
          <?php endif; ?>
        </h3>
        <p class="tb_version">v.<?php echo $theme['version']; ?></p>
        <p class="tb_desctription"><?php echo $theme['description']; ?></p>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php if ($additional_themes && !$is_bundle): ?>
<span class="clear border_ddd s_mb_30 s_pt_30"></span>

<h2>More themes</h2>

<div class="tb_listing tb_grid_view tb_more_themes tb_size_3 tb_gut_30">
  <?php foreach ($additional_themes as $theme): ?>
  <div class="tb_item_wrap">
    <div class="tb_item tb_theme">
      <div class="tb_thumb">
        <img src="<?php echo $theme['image_url']; ?>" />
        <div class="tb_actions align_right">
          <a class="s_button s_white s_h_30 s_icon_10 s_lense_10" target="_blank" href="<?php echo $theme['demo_url']; ?>">Live Preview</a>
          <a class="s_button s_white s_h_30 s_icon_10 s_cart_10" target="_blank" href="<?php echo $theme['buy_url']; ?>">Buy Theme</a>
        </div>
      </div>
      <div class="tb_item_info">
        <h3><?php echo $theme['name']; ?></h3>
        <p class="tb_version">v.<?php echo $theme['version']; ?></p>
        <p class="tb_desctription"><?php echo $theme['description']; ?></p>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ($is_bundle): ?>
<span class="clear s_pt_30"></span>

<a class="s_button s_white s_h_40 tbListAllThemes" href="<?php echo $tbUrl->generate('themes/themes'); ?>">Back to all themes</a>
<?php endif; ?>