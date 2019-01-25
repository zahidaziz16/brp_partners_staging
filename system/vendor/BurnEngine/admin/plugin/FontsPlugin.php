<?php

require_once TB_THEME_ROOT . '/model/data/MainFontsData.php';

class Theme_Admin_FontsPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    protected $default_settings;

    public function getConfigKey()
    {
        return 'font';
    }

    public function filterSettings(array &$font_settings)
    {
        $this->default_settings = MainFontsData::getDefaultFontItem();
        $default_fonts = MainFontsData::getFonts();
        foreach ($default_fonts as $key => $value) {
            $default_fonts[$key] = array_merge($this->default_settings, $value);
        }

        $fonts = TB_FormHelper::initLangVarsSimple($default_fonts, $font_settings, $this->engine->getEnabledLanguages());
        foreach ($this->engine->getEnabledLanguages() as $language_code => $language) {
            if (isset($font_settings[$language_code])) {
                foreach ($font_settings[$language_code] as $name => $section) {
                    $settings = isset($default_fonts[$name]) ? $default_fonts[$name] : $this->default_settings;
                    $fonts[$language_code][$name] = TB_FormHelper::initFlatVarsSimple($settings, $section);
                    if (!empty($default_fonts[$name]['built-in'])) {
                        // the elements key should be overwritten bu just changing its default value, because it cannot be changed from the UI (it's hidden)
                        $fonts[$language_code][$name]['elements'] = $default_fonts[$name]['elements'];
                    }
                }
            }
        }

        $font_settings = $fonts;
    }

    public function setDataForView(&$font_settings, TB_ViewDataBag $themeData)
    {
        $themeData->font_data = array(
            'google_font_list'      => $this->getModel('fonts')->getGoogleFontsList(),
            'built_font_families'   => $this->getModel('fonts')->getBuiltFontsList(),
            'built_font_variants'   => array('regular', 'bold', 'italic', 'bolditalic'),
            'default_font_settings' => $this->default_settings
        );
    }

    public function saveData($post_data)
    {
        if (isset($post_data['font'])) {
            $this->getModel('layoutBuilder')->cleanFontDataBeforePersist($post_data['font']);
        }

        return array(
            $this->getConfigKey() => $post_data['font']
        );
    }
}