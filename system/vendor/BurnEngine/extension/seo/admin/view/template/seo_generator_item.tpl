<fieldset data-item="<?php echo $generator_data['item']; ?>" data-context="<?php echo $generator_data['context']; ?>">
  <legend><?php echo $generator_data['title']; ?></legend>
  <div class="s_actions tbLanguagesChoice">
    <?php if (empty($generator_data['skip_languages'])): ?>
    <?php foreach ($languages as $code => $language): ?>
    <label class="s_language<?php if (count($languages) > 1): ?> s_checkbox<?php endif; ?>">
      <input type="hidden" name="seo_general[<?php echo $generator_data['item']; ?>][<?php echo $generator_data['context']; ?>][languages][<?php echo $code; ?>]" value="<?php echo count($languages) > 1 ? 0 : 1; ?>" />
      <?php if (count($languages) > 1): ?>
      <input type="checkbox" name="seo_general[<?php echo $generator_data['item']; ?>][<?php echo $generator_data['context']; ?>][languages][<?php echo $code; ?>]"<?php if($seo_general[$generator_data['item']][$generator_data['context']]['languages'][$code] == '1') echo ' checked="checked"';?> value="1" />
      <?php endif; ?>
      <span class="uppercase"><img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?></span>
    </label>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <div class="tb_wrap s_mb_5">
    <div class="tb_col tb_5_8">
      <div class="input-group tb_1_1">
        <input class="tb_1_1 tb_mb_10" type="text" name="seo_general[<?php echo $generator_data['item']; ?>][<?php echo $generator_data['context']; ?>][pattern]" value="<?php echo $seo_general[$generator_data['item']][$generator_data['context']]['pattern']; ?>" />
        <div class="input-group-btn">
          <a href="javascript:;" class="s_button s_white s_h_30 s_icon_10 s_cog_10 tbSettingsButton"></a>
        </div>
      </div>
    </div>
    <div class="tb_col tb_3_8">
      <div class="s_buttons_group s_justified s_h_30">
        <a href="javascript:;" class="s_button s_white s_h_30 tbPreview"><span class="s_icon_10 s_lense_10">Preview</span></a>
        <?php if (!$generator_data['preview_only']): ?>
        <a href="javascript:;" class="s_button s_white s_h_30 tbGenerate"><span class="s_icon_10 s_lightning_10">Generate</span></a>
        <a href="javascript:;" class="s_button s_white s_h_30 tbClear"><span class="s_icon_10 s_delete_10">Clear</span></a>
        <?php endif ;?>
      </div>
    </div>
  </div>
  <div class="tb_wrap tbSettingsWrap" style="display: none;">
    <div class="tb_col tb_1_1">
      <p>
        <?php foreach ($generator_data['vars'] as $var): ?>
        <a href="javascript:;" class="tbPatternItem">[<?php echo $var; ?>]</a> &nbsp;&nbsp;
        <?php endforeach; ?>
      </p>
    </div>
  </div>
  <div class="tb_wrap s_mt_15">
    <?php if(isset($seo_general[$generator_data['item']][$generator_data['context']]['autofill'])): ?>
    <div class="s_row_2 tb_col tb_1_6">
      <label for="extension_seo_generator_products_keyword_autofill">Autofill</label>
      <input type="hidden" name="seo_general[<?php echo $generator_data['item']; ?>][<?php echo $generator_data['context']; ?>][autofill]" value="0" />
      <label class="tb_toggle"><input id="extension_seo_generator_products_keyword_autofill" type="checkbox" name="seo_general[<?php echo $generator_data['item']; ?>][<?php echo $generator_data['context']; ?>][autofill]"<?php if($seo_general[$generator_data['item']][$generator_data['context']]['autofill'] == '1') echo ' checked="checked"';?> value="1" /><span></span><span></span></label>
    </div>
    <?php endif; ?>
    <?php if(isset($seo_general[$generator_data['item']][$generator_data['context']]['skip_existing'])): ?>
    <div class="s_row_2 tb_col tb_1_6">
      <label for="extension_seo_generator_products_keyword_skip_existing">Skip existing</label>
      <input type="hidden" name="seo_general[<?php echo $generator_data['item']; ?>][<?php echo $generator_data['context']; ?>][skip_existing]" value="0" />
      <label class="tb_toggle"><input id="extension_seo_generator_products_keyword_skip_existing" type="checkbox" name="seo_general[<?php echo $generator_data['item']; ?>][<?php echo $generator_data['context']; ?>][skip_existing]"<?php if($seo_general[$generator_data['item']][$generator_data['context']]['skip_existing'] == '1') echo ' checked="checked"';?> value="1" /><span></span><span></span></label>
    </div>
    <?php endif; ?>
    <?php if(isset($seo_general[$generator_data['item']][$generator_data['context']]['transliterate'])): ?>
    <div class="s_row_2 tb_col tb_1_6">
      <label for="extension_seo_generator_products_keyword_transliterate">Transliterate</label>
      <input type="hidden" name="seo_general[<?php echo $generator_data['item']; ?>][<?php echo $generator_data['context']; ?>][transliterate]" value="0" />
      <label class="tb_toggle"><input id="extension_seo_generator_products_keyword_transliterate" type="checkbox" name="seo_general[<?php echo $generator_data['item']; ?>][<?php echo $generator_data['context']; ?>][transliterate]"<?php if($seo_general[$generator_data['item']][$generator_data['context']]['transliterate'] == '1') echo ' checked="checked"';?> value="1" /><span></span><span></span></label>
    </div>
    <?php endif; ?>
  </div>
</fieldset>