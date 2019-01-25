<div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

  <h2>Twitter</h2>

  <?php if (count($languages) > 1): ?>
  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <?php foreach ($languages as $language): ?>
      <li class="s_language">
        <a href="#twitter_settings_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
          <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <?php foreach (array_keys($languages) as $language_code): $settings = $theme_settings['twitter'][$language_code]; ?>
  <div id="twitter_settings_language_<?php echo $language_code; ?>">

    <fieldset>
      <legend>Default Widget</legend>
      <div class="s_row_2 clear s_full opt_default">
        <textarea name="twitter[<?php echo $language_code; ?>][code]" rows="6"><?php echo $settings['code']; ?></textarea>
      </div>
    </fieldset>

    <?php if (function_exists('curl_init')): ?>
    <fieldset>
      <legend>API Settings</legend>
      <div class="tb_wrap tb_gut_30 s_mb_15">
        <div class="s_row_2 tb_col tb_1_2 opt_custom">
          <label><?php echo $text_label_footer_twitter_username; ?></label>
          <div class="s_full">
            <input type="text" name="twitter[<?php echo $language_code; ?>][username]" value="<?php echo $settings['username']; ?>" />
          </div>
        </div>
      </div>

      <div class="tb_wrap tb_gut_30">
        <div class="s_row_2 tb_col tb_1_2 opt_custom">
          <label>API key</label>
          <div class="s_full">
            <input type="text" name="twitter[<?php echo $language_code; ?>][consumer_key]" value="<?php echo $settings['consumer_key']; ?>" />
          </div>
        </div>

        <div class="s_row_2 tb_col tb_1_2 opt_custom">
          <label>API secret</label>
          <div class="s_full">
            <input type="text" name="twitter[<?php echo $language_code; ?>][consumer_secret]" value="<?php echo $settings['consumer_secret']; ?>" />
          </div>
        </div>
      </div>

      <div class="tb_wrap tb_gut_30">
        <div class="s_row_2 tb_col tb_1_2 opt_custom">
          <label>Access token</label>
          <div class="s_full">
            <input type="text" name="twitter[<?php echo $language_code; ?>][access_token]" value="<?php echo $settings['access_token']; ?>" />
          </div>
        </div>

        <div class="s_row_2 tb_col tb_1_2 opt_custom">
          <label>Access token secret</label>
          <div class="s_full">
            <input type="text" name="twitter[<?php echo $language_code; ?>][access_secret]" value="<?php echo $settings['access_secret']; ?>" />
          </div>
        </div>
      </div>
    </fieldset>

    <?php else: ?>

    <div class="s_row_2 clearfix opt_custom">
      <p>
        The php extension <strong>curl</strong> does not seem to be available in your server configuration. Please, ask your server administrator to install it in order to use the custom twitter widget.
      </p>
    </div>

    <?php endif; ?>

  </div>
  <?php endforeach; ?>
</div>