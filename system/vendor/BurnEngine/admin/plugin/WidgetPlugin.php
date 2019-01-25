<?php

class Theme_Admin_WidgetPlugin extends TB_ExtensionPlugin
{
    public function configure(TB_ViewDataBag $themeData)
    {
        $this->eventDispatcher->connect('core:persistWidget', array($this, 'persistWidget'));
    }

    public function persistWidget(sfEvent $event, array $widget_settings)
    {
        if (empty($widget_settings['preset_id'])) {
            return $widget_settings;
        }

        if (!$preset = $this->engine->getSettingsModel('preset', 0)->getScopeSettings($widget_settings['preset_id'])) {
            return $widget_settings;
        }

        foreach (array('box', 'title') as $style_group) {

            $styles = array();

            if (isset($widget_settings[$style_group . '_styles']['colors'])) {
                if (!empty($preset['styles'][$style_group]['colors'])) {
                    $styles['colors'] = array_diff_key($widget_settings[$style_group . '_styles']['colors'], $preset['styles'][$style_group]['colors']);
                } else {
                    // The widget color section does not exist in the preset, so we allow saving custom colors for this section even though a preset has been applied
                    $styles['colors'] = $widget_settings[$style_group . '_styles']['colors'];
                }
            }

            if (isset($widget_settings[$style_group . '_styles']['font']) && !empty($preset['styles'][$style_group]['font'])) {
                $fonts = array();
                foreach ($widget_settings[$style_group . '_styles']['font'] as $language_code => &$font_settings) {
                    $preset_language_code = $language_code;

                    if (!isset($preset['styles'][$style_group]['font'][$language_code])) {
                        $preset_language_code = key($preset['styles'][$style_group]['font'][$language_code]);
                    }

                    $fonts[$language_code] = array_diff_key($font_settings, $preset['styles'][$style_group]['font'][$preset_language_code]);
                }
                if ($fonts) {
                    $styles['font'] = $fonts;
                }
            }

            if ($style_group == 'box') {
                $layout = array_diff_key($widget_settings['box_styles']['layout'], $preset['styles']['box']['layout']);
                if (!empty($layout)) {
                    $styles['layout'] = $layout;
                }
            }

            $widget_settings[$style_group . '_styles'] = $styles;
        }

        return $widget_settings;
    }
}