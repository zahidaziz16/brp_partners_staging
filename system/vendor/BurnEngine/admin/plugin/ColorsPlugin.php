<?php

require_once TB_THEME_ROOT . '/model/data/MainColorsData.php';


class Theme_Admin_ColorsPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    protected $default_colors;

    public function getConfigKey()
    {
        return 'colors';
    }

    public function filterSettings(array &$color_settings)
    {
        $this->default_colors = MainColorsData::getColors();

        $theme_config = $this->engine->getThemeConfig();
        if (isset($theme_config['colors']['theme'])) {
            $this->default_colors = array_replace_recursive($this->default_colors, $theme_config['colors']['theme']);

            foreach ($this->default_colors as $group_key => &$sections) {
                foreach ($sections as $section_key => $section_values) {
                    if (0 === strpos($section_key, '_')) {
                        continue;
                    }
                    if (isset($section_values['render_before'])) {
                        $position = array_search($section_values['render_before'], array_keys($sections));
                        unset($section_values['render_before']);
                        unset($sections[$section_key]);

                        TB_Utils::arrayInsert($sections, $position, array($section_key => $section_values));
                    }
                }
            }
        }

        if (isset($color_settings['custom'])) {
            $this->default_colors['custom'] = array_merge($this->default_colors['custom'], $color_settings['custom']);
        }

        TB_ColorSchemer::getInstance()->filterThemeColors($color_settings, $this->default_colors);

        $this->getThemeData()->colors = $color_settings;
    }

    public function setDataForView(&$color_settings, TB_ViewDataBag $themeData)
    {
        $inherit_menu = MainColorsData::getInheritMenuColors();

        $theme_config = $this->engine->getThemeConfig();
        if (isset($theme_config['colors']['inherit_menu'])) {
            $inherit_menu = array_replace($inherit_menu, $theme_config['colors']['inherit_menu']);
        }
        $inherit_menu = array_values($inherit_menu);

        foreach ($inherit_menu as &$item) {
            $inherit_key = $item['inherit_key'];
            if (0 === strpos($inherit_key, 'theme:')) {
                $inherit_key = TB_Utils::strGetAfter($inherit_key, 'theme:');
            }

            list($group, $section) = explode('.', $inherit_key);

            if (!isset($color_settings[$group][$section])) {
                throw new Exception('Cannot resolve inherit menu: ' . $item['inherit_key']);
            }

            $item['label'] = $color_settings[$group]['_label'] . ' <span>&#9654;</span> ' . $color_settings[$group][$section]['label'];
            $item['color'] = $color_settings[$group][$section]['color'];
        }

        $themeData->inherit_menu = $inherit_menu;
    }

    public function saveData($post_data)
    {
        if (!isset($post_data[$this->getConfigKey()]['custom'])) {
            $post_data[$this->getConfigKey()]['custom'] = array();
        }

        $colors = $post_data[$this->getConfigKey()];

        foreach ($colors as $group_key => &$sections) {
            foreach ($sections as $section_key => &$section_values) {
                if ($section_values['inherit'] != 2) {
                    unset($section_values['inherit_key']);
                }
            }
        }

        unset($_SESSION['tb_save_colors']);

        return array(
            $this->getConfigKey() => $colors
        );
    }
}