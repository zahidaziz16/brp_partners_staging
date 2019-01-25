<div<?php if (count($languages) > 1) echo ' class="tb_tabs tb_fly_tabs tbLanguageTabs"'; ?>>

  <h2>Facebook</h2>

  <?php if (count($languages) > 1): ?>
  <div class="tb_tabs_nav">
    <ul class="clearfix">
      <?php foreach ($languages as $language): ?>
      <li class="s_language">
        <a href="#facebook_settings_language_<?php echo $language['code']; ?>" title="<?php echo $language['name']; ?>">
          <img class="inline" src="<?php echo $language['url'] . $language['image']; ?>" /> <?php echo $language['code']; ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <?php foreach ($languages as $language): ?>
  <?php $language_code = $language['code']; ?>
  <div id="facebook_settings_language_<?php echo $language_code; ?>">

    <div class="s_row_1">
      <label for="footer_facebook_yes"><?php echo $text_enabled; ?></label>
      <input type="hidden" name="facebook[<?php echo $language_code; ?>][sdk_enabled]" value="0" />
      <label class="tb_toggle"><input id="footer_facebook_yes" type="checkbox" name="facebook[<?php echo $language_code; ?>][sdk_enabled]" value="1"<?php if($theme_settings['facebook'][$language_code]['sdk_enabled'] == '1') echo ' checked="checked"';?> /><span></span><span></span></label>
    </div>

    <div class="s_row_1">
      <label>App ID</label>
      <input type="text" name="facebook[<?php echo $language_code; ?>][app_id]" value="<?php echo $theme_settings['facebook'][$language_code]['app_id']; ?>" />
    </div>

    <div class="s_row_1">
      <label><?php echo $text_label_footer_facebook_locale; ?></label>
      <?php $facebook_locale = $theme_settings['facebook'][$language_code]['locale']; ?>
      <div class="s_select">
        <select name="facebook[<?php echo $language_code; ?>][locale]">
          <option value="af_ZA"<?php if ($facebook_locale == 'af_ZA') echo ' selected="selected"'; ?>>Afrikaans</option>
          <option value="sq_AL"<?php if ($facebook_locale == 'sq_AL') echo ' selected="selected"'; ?>>Albanian</option>
          <option value="ar_AR"<?php if ($facebook_locale == 'ar_AR') echo ' selected="selected"'; ?>>Arabic</option>
          <option value="az_AZ"<?php if ($facebook_locale == 'az_AZ') echo ' selected="selected"'; ?>>Azeri</option>
          <option value="hy_AM"<?php if ($facebook_locale == 'hy_AM') echo ' selected="selected"'; ?>>Armenian</option>
          <option value="be_BY"<?php if ($facebook_locale == 'be_BY') echo ' selected="selected"'; ?>>Belarusian</option>
          <option value="bg_BG"<?php if ($facebook_locale == 'bg_BG') echo ' selected="selected"'; ?>>Bulgarian</option>
          <option value="eu_ES"<?php if ($facebook_locale == 'eu_ES') echo ' selected="selected"'; ?>>Basque</option>
          <option value="bn_IN"<?php if ($facebook_locale == 'bn_IN') echo ' selected="selected"'; ?>>Bengali</option>
          <option value="bs_BA"<?php if ($facebook_locale == 'bs_BA') echo ' selected="selected"'; ?>>Bosnian</option>
          <option value="ca_ES"<?php if ($facebook_locale == 'ca_ES') echo ' selected="selected"'; ?>>Catalan</option>
          <option value="cs_CZ"<?php if ($facebook_locale == 'cs_CZ') echo ' selected="selected"'; ?>>Czech</option>
          <option value="hr_HR"<?php if ($facebook_locale == 'hr_HR') echo ' selected="selected"'; ?>>Croatian</option>
          <option value="da_DK"<?php if ($facebook_locale == 'da_DK') echo ' selected="selected"'; ?>>Danish</option>
          <option value="nl_NL"<?php if ($facebook_locale == 'nl_NL') echo ' selected="selected"'; ?>>Dutch</option>
          <option value="en_US"<?php if ($facebook_locale == 'en_US') echo ' selected="selected"'; ?>>English</option>
          <option value="eo_EO"<?php if ($facebook_locale == 'eo_EO') echo ' selected="selected"'; ?>>Esperanto</option>
          <option value="et_EE"<?php if ($facebook_locale == 'et_EE') echo ' selected="selected"'; ?>>Estonian</option>
          <option value="fi_FI"<?php if ($facebook_locale == 'fi_FI') echo ' selected="selected"'; ?>>Finnish</option>
          <option value="fo_FO"<?php if ($facebook_locale == 'fo_FO') echo ' selected="selected"'; ?>>Faroese</option>
          <option value="tl_PH"<?php if ($facebook_locale == 'tl_PH') echo ' selected="selected"'; ?>>Filipino</option>
          <option value="fr_FR"<?php if ($facebook_locale == 'fr_FR') echo ' selected="selected"'; ?>>French</option>
          <option value="fy_NL"<?php if ($facebook_locale == 'fy_NL') echo ' selected="selected"'; ?>>Frisian</option>
          <option value="de_DE"<?php if ($facebook_locale == 'de_DE') echo ' selected="selected"'; ?>>German</option>
          <option value="el_GR"<?php if ($facebook_locale == 'el_GR') echo ' selected="selected"'; ?>>Greek</option>
          <option value="gl_ES"<?php if ($facebook_locale == 'gl_ES') echo ' selected="selected"'; ?>>Galician</option>
          <option value="ka_GE"<?php if ($facebook_locale == 'ka_GE') echo ' selected="selected"'; ?>>Georgian</option>
          <option value="he_IL"<?php if ($facebook_locale == 'he_IL') echo ' selected="selected"'; ?>>Hebrew</option>
          <option value="hi_IN"<?php if ($facebook_locale == 'hi_IN') echo ' selected="selected"'; ?>>Hindi</option>
          <option value="hu_HU"<?php if ($facebook_locale == 'hu_HU') echo ' selected="selected"'; ?>>Hungarian</option>
          <option value="ga_IE"<?php if ($facebook_locale == 'ga_IE') echo ' selected="selected"'; ?>>Irish</option>
          <option value="id_ID"<?php if ($facebook_locale == 'id_ID') echo ' selected="selected"'; ?>>Indonesian</option>
          <option value="is_IS"<?php if ($facebook_locale == 'is_IS') echo ' selected="selected"'; ?>>Icelandic</option>
          <option value="it_IT"<?php if ($facebook_locale == 'it_IT') echo ' selected="selected"'; ?>>Italian</option>
          <option value="ja_JP"<?php if ($facebook_locale == 'ja_JP') echo ' selected="selected"'; ?>>Japanese</option>
          <option value="km_KH"<?php if ($facebook_locale == 'km_KH') echo ' selected="selected"'; ?>>Khmer</option>
          <option value="ko_KR"<?php if ($facebook_locale == 'ko_KR') echo ' selected="selected"'; ?>>Korean</option>
          <option value="ku_TR"<?php if ($facebook_locale == 'ku_TR') echo ' selected="selected"'; ?>>Kurdish</option>
          <option value="lt_LT"<?php if ($facebook_locale == 'lt_LT') echo ' selected="selected"'; ?>>Lithuanian</option>
          <option value="lv_LV"<?php if ($facebook_locale == 'lv_LV') echo ' selected="selected"'; ?>>Latvian</option>
          <option value="ml_IN"<?php if ($facebook_locale == 'ml_IN') echo ' selected="selected"'; ?>>Malayalam</option>
          <option value="ms_MY"<?php if ($facebook_locale == 'ms_MY') echo ' selected="selected"'; ?>>Malay</option>
          <option value="ne_NP"<?php if ($facebook_locale == 'ne_NP') echo ' selected="selected"'; ?>>Nepali</option>
          <option value="nn_NO"<?php if ($facebook_locale == 'nn_NO') echo ' selected="selected"'; ?>>Norwegian</option>
          <option value="pa_IN"<?php if ($facebook_locale == 'pa_IN') echo ' selected="selected"'; ?>>Punjabi</option>
          <option value="pl_PL"<?php if ($facebook_locale == 'pl_PL') echo ' selected="selected"'; ?>>Polish</option>
          <option value="fa_IR"<?php if ($facebook_locale == 'fa_IR') echo ' selected="selected"'; ?>>Persian</option>
          <option value="pt_BR"<?php if ($facebook_locale == 'pt_BR') echo ' selected="selected"'; ?>>Portuguese (Brazil)</option>
          <option value="pt_PT"<?php if ($facebook_locale == 'pt_PT') echo ' selected="selected"'; ?>>Portuguese (Portugal)</option>
          <option value="ro_RO"<?php if ($facebook_locale == 'ro_RO') echo ' selected="selected"'; ?>>Romanian</option>
          <option value="ru_RU"<?php if ($facebook_locale == 'ru_RU') echo ' selected="selected"'; ?>>Russian</option>
          <option value="sk_SK"<?php if ($facebook_locale == 'sk_SK') echo ' selected="selected"'; ?>>Slovak</option>
          <option value="es_ES"<?php if ($facebook_locale == 'es_ES') echo ' selected="selected"'; ?>>Spanish</option>
          <option value="sl_SI"<?php if ($facebook_locale == 'sl_SI') echo ' selected="selected"'; ?>>Slovenian</option>
          <option value="sr_RS"<?php if ($facebook_locale == 'sr_RS') echo ' selected="selected"'; ?>>Serbian</option>
          <option value="sv_SE"<?php if ($facebook_locale == 'sv_SE') echo ' selected="selected"'; ?>>Swedish</option>
          <option value="sw_KE"<?php if ($facebook_locale == 'sw_KE') echo ' selected="selected"'; ?>>Swahili</option>
          <option value="zh_CN"<?php if ($facebook_locale == 'zh_CN') echo ' selected="selected"'; ?>>Simplified Chinese (China)</option>
          <option value="ta_IN"<?php if ($facebook_locale == 'ta_IN') echo ' selected="selected"'; ?>>Tamil</option>
          <option value="te_IN"<?php if ($facebook_locale == 'te_IN') echo ' selected="selected"'; ?>>Telugu</option>
          <option value="th_TH"<?php if ($facebook_locale == 'th_TH') echo ' selected="selected"'; ?>>Thai</option>
          <option value="zh_HK"<?php if ($facebook_locale == 'zh_HK') echo ' selected="selected"'; ?>>Traditional Chinese (Hong Kong)</option>
          <option value="zh_TW"<?php if ($facebook_locale == 'zh_TW') echo ' selected="selected"'; ?>>Traditional Chinese (Taiwan)</option>
          <option value="tr_TR"<?php if ($facebook_locale == 'tr_TR') echo ' selected="selected"'; ?>>Turkish</option>
          <option value="uk_UA"<?php if ($facebook_locale == 'uk_UA') echo ' selected="selected"'; ?>>Ukrainian</option>
          <option value="vi_VN"<?php if ($facebook_locale == 'vi_VN') echo ' selected="selected"'; ?>>Vietnamese</option>
          <option value="cy_GB"<?php if ($facebook_locale == 'cy_GB') echo ' selected="selected"'; ?>>Welsh</option>
        </select>
      </div>
    </div>

  </div>
  <?php endforeach; ?>
</div>