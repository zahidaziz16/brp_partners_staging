<?php

require_once TB_THEME_ROOT . '/library/WidgetBase.php';
require_once TB_THEME_ROOT . '/model/data/WidgetData.php';
require_once TB_THEME_ROOT . '/model/data/MainFontsData.php';

abstract class TB_Widget extends TB_WidgetBase
{
    /**
     * @var TB_Widget
     */
    protected $parent;

    public function setParent(TB_Widget $parent)
    {
        $this->parent = $parent;
    }

    public function onFilterSystem(array &$settings, $area = 'admin')
    {
        if (!isset($settings['widget_name'])) {
            $settings['widget_name'] = $this->getName();
        }

        if (!isset($settings['preset_id']) || !in_array($settings['preset_id'], $this->getModel('default')->getPresetIds())) {
            $settings['preset_id'] = '';
        }

        $settings = array_replace($settings, $this->filterCommonSettingsData($settings, $area));

        if ($this->engine->getContext()->getArea() == 'catalog' && $this->engine->getConfig('widgets_fallback_first_language') && isset($settings['lang']) && !isset($settings['lang'][$this->language_code])) {
            $settings['lang'][$this->language_code] = reset($settings['lang']);
        }
    }

    public function onEditSystem(array &$settings)
    {
        foreach (array('box', 'title') as $style_section_id) {
            if (isset($settings[$style_section_id . '_styles']['colors'])) {
                TB_ColorSchemer::getInstance()->filterWidgetColors($settings[$style_section_id . '_styles']['colors'], $this->getDefaultColors($style_section_id), $this->getId());
            }

            if (!empty($settings[$style_section_id . '_styles']['background']['rows'])) {
                foreach ($settings[$style_section_id . '_styles']['background']['rows'] as &$bg_row) {
                    if ($bg_row['background_type'] == 'image') {
                        if (!empty($bg_row['image'])  && file_exists(DIR_IMAGE . $bg_row['image'])) {
                            $bg_row['preview'] = $this->engine->getOcToolImage()->resize($bg_row['image'], 100, 100);
                        } else {
                            $bg_row['image'] = '';
                            $bg_row['preview'] = $this->getThemeModel()->getNoImage();
                        }
                    }
                }
            }
        }

        $settings['widget_admin_title'] = $this->getName();
        $settings['widget_admin_prefix'] = TB_Utils::underscore($this->getName());
    }

    public function onPersistSystem(array &$settings)
    {
        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->getModel('layoutBuilder');

        if ($this->hasBoxStyles()) {
            $layoutBuilderModel->cleanSettingsDataBeforePersist($settings['box_styles']);
        }

        if ($this->hasTitleStyles()) {
            $has_title = false;

            if (isset($settings['lang'])) {
                foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
                    if (isset($settings['lang'][$language_code]['title']) && trim($settings['lang'][$language_code]['title'])) {
                        $has_title = true;
                        break;
                    }
                }
            }

            if (!empty($settings['block_title']) && $this instanceof Theme_SystemWidget
                || $this instanceof Theme_GroupWidget
                || $this instanceof Theme_BlockGroupWidget
                || $this instanceof Theme_OpenCartWidget)
            {
                $has_title = true;
            }

            if ($has_title) {
                $layoutBuilderModel->cleanSettingsDataBeforePersist($settings['title_styles']);
            } else {
                unset($settings['title_styles']);
            }
        }

        $settings = $this->engine->getEventDispatcher()->filter(new sfEvent($this, 'core:persistWidget'), $settings)->getReturnValue();
    }

    public function allowAddToAreaContentCache()
    {
        return true;
    }

    protected function filterStylesData(array &$result, array $default_vars, $style_section_id, $preset_id, $area)
    {
        $result = TB_FormHelper::initFlatVarsSimple($default_vars, $result);

        if ($presets_data = $this->getPresetData($preset_id)) {
            $presets_data = $presets_data['styles'][$style_section_id];
        }

        $is_admin = $area == 'admin';

        // Layout
        $result['layout'] = TB_FormHelper::initFlatVarsSimple($default_vars['layout'], $result['layout']);
        if ($preset_id) {
            if ($is_admin && !empty($presets_data['layout'])) {
                $result['layout'] = array_replace_recursive($result['layout'], $presets_data['layout']);
            } else {
                $result['layout'] = array_intersect_key($result['layout'], array(
                    'extra_class' => ''
                ));
            }
        }

        // Box Shadows
        $result['box_shadow'] = TB_FormHelper::initFlatVarsSimple($default_vars['box_shadow'], $result['box_shadow']);
        foreach ($result['box_shadow']['rows'] as &$row) {
            $row = TB_FormHelper::initFlatVarsSimple(WidgetData::getBoxShadowRow(), $row);
        }

        if ($preset_id) {
            if ($is_admin && !empty($presets_data['box_shadow'])) {
                $result['box_shadow'] = array_replace_recursive($result['box_shadow'], $presets_data['box_shadow']);
            } else {
                unset($result['box_shadow']);
            }
        }

        // Background
        if (!empty($result['background']['solid_color_inherit_key'])) {
            $default_vars['background']['solid_color_inherit_key'] = '';
        }
        $result['background'] = TB_FormHelper::initFlatVarsSimple($default_vars['background'], $result['background']);
        if (isset($result['background']['solid_color_inherit_key'])) {
            $result['background']['solid_color'] = TB_ColorSchemer::getInstance()->resolveParentColor($this->themeData->colors, $result['background']['solid_color_inherit_key']);
        }

        foreach ($result['background']['rows'] as &$bg_row) {
            switch ($bg_row['background_type']) {
                case 'gradient':
                    $bg_row = TB_FormHelper::initFlatVarsSimple(WidgetData::getBackgroundGradientRow(), $bg_row);
                    foreach ($bg_row['colors'] as &$color_row) {
                        $color_row = TB_FormHelper::initFlatVarsSimple(WidgetData::getBackgroundColorRow(), $color_row);
                    }
                    break;
                case 'image':
                    $bg_row = TB_FormHelper::initFlatVarsSimple(WidgetData::getBackgroundImageRow(), $bg_row);
                    break;
            }
        }

        if ($preset_id) {
            if ($is_admin && !empty($presets_data['background'])) {
                $result['background'] = array_replace_recursive($result['background'], $presets_data['background']);
            } else {
                unset($result['background']);
            }
        }

        // Border
        $result['border'] = TB_FormHelper::initFlatVarsSimple($default_vars['border'], $result['border']);

        foreach (array('top', 'right', 'bottom', 'left') as $side) {
            $result['border'][$side] = TB_FormHelper::initFlatVarsSimple(WidgetData::getBorderRow(), $result['border'][$side]);
        }

        // Border Radius
        $result['border_radius'] = TB_FormHelper::initFlatVarsSimple($default_vars['border_radius'], $result['border_radius']);

        if ($preset_id) {
            if ($is_admin) {
                $result['border']        = array_replace_recursive($result['border'], $presets_data['border']);
                $result['border_radius'] = array_replace_recursive($result['border_radius'], $presets_data['border_radius']);
            } else {
                unset($result['border'], $result['border_radius']);
            }
        }

        // Colors
        $default_colors = $this->getDefaultColors($style_section_id);

        if ($preset_id && !empty($presets_data['colors'])) {
            if (empty($result['colors'])) {
                $result['colors'] = array();
            }
            foreach (array_keys($presets_data['colors']) as $preset_color_group) {
                if ($is_admin) {
                    if (!isset($default_colors[$preset_color_group])) {
                        $default_colors[$preset_color_group] = array();
                    }
                    $result['colors'][$preset_color_group] = array_replace_recursive($default_colors[$preset_color_group], $presets_data['colors'][$preset_color_group]);
                    foreach (array_keys($result['colors'][$preset_color_group]) as $key) {
                        if (0 === strpos($key, '_')) {
                            continue;
                        }
                        $result['colors'][$preset_color_group][$key]['inherit'] = 0;
                    }
                } else {
                    unset($result['colors'][$preset_color_group]);
                }
            }
        }

        if (!empty($result['colors'])) {
            $temp_colors = $result['colors'];
            foreach ($temp_colors as $group_key => &$sections) {
                foreach ($sections as $section_key => &$section_values) {
                    if (0 === strpos($section_key, '_')) {
                        continue;
                    }
                    if (isset($default_colors[$group_key][$section_key])) {
                        unset($section_values['elements']);
                        unset($section_values['important']);
                        unset($section_values['property']);
                        if ($section_values['inherit'] != 2) {
                            unset($section_values['inherit_key']);
                        } else {
                            $section_values['force_print'] = 1;
                        }
                        $section_values = TB_FormHelper::initFlatVarsSimple($default_colors[$group_key][$section_key], $section_values);
                    } else {
                        unset($result['colors'][$group_key][$section_key]);
                    }
                }
                if (empty($result['colors'][$group_key])) {
                    unset($result['colors'][$group_key]);
                } else {
                    $result['colors'][$group_key] = $sections;
                }
            }
        } else
        if (!$preset_id) {
            $result['colors'] = $default_colors;
        }

        // Font
        $default_fonts = $default_vars['font'];
        if ($preset_id && !empty($presets_data['font'])) {
            $current_fonts = isset($result['font'][$this->language_code]) ? $result['font'][$this->language_code] : reset($result['font']);
            $preset_fonts  = isset($presets_data['font'][$this->language_code]) ? $presets_data['font'][$this->language_code] : reset($presets_data['font']);

            if (!empty($preset_fonts)) {
                foreach (array_keys($preset_fonts) as $preset_font_key) {
                    if (!isset($current_fonts[$preset_font_key])) {
                        continue;
                    }

                    if ($is_admin) {
                        $current_fonts[$preset_font_key] = array_merge_recursive($current_fonts[$preset_font_key], $preset_fonts[$preset_font_key]);
                    } else {
                        unset($current_fonts[$preset_font_key]);
                    }
                }
            }

            if ($style_section_id != 'title' || !empty($current_fonts)) {
                if (!empty($current_fonts)) {
                    $default_fonts = array_intersect_key($default_vars['font'], $current_fonts);
                }
            } else {
                $default_fonts = $preset_fonts;
            }
        }

        $result['font'] = TB_FormHelper::initLangVarsSimple($default_fonts, $result['font'], $this->engine->getEnabledLanguages());

        foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
            if (!isset($result['font'][$language_code])) {
                continue;
            }

            foreach ($result['font'][$language_code] as $name => &$section) {
                unset($section['section_name']);
                unset($section['elements']);
                unset($section['show_built_styles']);
                unset($section['multiple_variants']);
                if ($section['family'] == 'inherit') {
                    $section['type'] = '';
                }

                if (!$is_admin) {
                    // Do not merge the line-height with the default value if it is not present
                    if (!isset($section['line-height'])) {
                        unset($default_vars['font'][$name]['line-height']);
                    } else {
                        $default_vars['font'][$name]['line-height'] = $section['line-height'];
                    }
                }

                $section = TB_FormHelper::initFlatVarsSimple($default_vars['font'][$name], $section);
            }
        }
    }

    protected function filterCommonSettingsData($data = array(), $area)
    {
        $default_vars = array();
        $result = array();

        if ($this->hasBoxStyles()) {
            $default_vars['box_styles'] = WidgetData::getBoxStyleVars();
            $default_vars['box_styles']['font'] = $this->getDefaultBoxFonts();
            foreach ($default_vars['box_styles']['font'] as $key => $value) {
                $default_vars['box_styles']['font'][$key] = array_merge(MainFontsData::getDefaultFontItem(), $value);
            }
        }

        if ($this->hasTitleStyles()) {
            $default_vars['title_styles'] = WidgetData::getTitleStyleVars();
            $default_vars['title_styles']['font'] = $this->getDefaultTitleFonts();
            foreach ($default_vars['title_styles']['font'] as $key => $value) {
                $default_vars['title_styles']['font'][$key] = array_merge(MainFontsData::getDefaultFontItem(), $value);
            }
        }

        if (method_exists($this, 'modifyDefaultCommonVars')) {
            $this->modifyDefaultCommonVars($default_vars);
        }

        if ($this->hasBoxStyles()) {
            if (!isset($data['box_styles'])) {
                $data['box_styles'] = array();
            }
            $result['box_styles'] = $data['box_styles'];
            $this->filterStylesData($result['box_styles'], $default_vars['box_styles'], 'box', !empty($data['preset_id']) ? $data['preset_id'] : false, $area);
        }

        if ($this->hasTitleStyles()) {
            if (!isset($data['title_styles'])) {
                $data['title_styles'] = array();
            }
            $result['title_styles'] = $data['title_styles'];
            $this->filterStylesData($result['title_styles'], $default_vars['title_styles'], 'title', !empty($data['preset_id']) ? $data['preset_id'] : false, $area);
        }

        return $result;
    }

    protected function getDefaultColors($style_section_id)
    {
        return $this->{'getDefault' . ucfirst($style_section_id) . 'Colors'}();
    }

    public function getDefaultBoxFonts()
    {
        return WidgetData::getFonts('box');
    }

    public function getDefaultTitleFonts()
    {
        return WidgetData::getFonts('title');
    }

    public function getDefaultBoxColors()
    {
        return WidgetData::getColors('box');
    }

    public function getDefaultTitleColors()
    {
        return WidgetData::getColors('title');
    }

    protected function getDistanceClasses($type)
    {
        $styles   = $this->getSettings($type . '_styles');

        if (!$styles || !isset($styles['layout'])) {
            return '';
        }

        $layout   = $styles['layout'];
        $lang_dir = $this->themeData->language_direction;

        $classes = !empty($layout['extra_class']) ? ' ' . $layout['extra_class'] : '';

        if (empty($this->settings['preset_id'])) {
            $margin_left   = $lang_dir == 'rtl' && !empty($layout['margin_rtl_mode'])  ? $layout['margin_right']  : $layout['margin_left'];
            $margin_right  = $lang_dir == 'rtl' && !empty($layout['margin_rtl_mode'])  ? $layout['margin_left']   : $layout['margin_right'];
            $padding_left  = $lang_dir == 'rtl' && !empty($layout['padding_rtl_mode']) ? $layout['padding_right'] : $layout['padding_left'];
            $padding_right = $lang_dir == 'rtl' && !empty($layout['padding_rtl_mode']) ? $layout['padding_left']  : $layout['padding_right'];
            $classes .= $layout['margin_top']     != 0 ? ' tb_mt_' . $layout['margin_top']     : '';
            $classes .= $margin_right             != 0 ? ' tb_mr_' . $margin_right             : '';
            $classes .= $layout['margin_bottom']  != 0 ? ' tb_mb_' . $layout['margin_bottom']  : '';
            $classes .= $margin_left              != 0 ? ' tb_ml_' . $margin_left              : '';
            $classes .= $layout['padding_top']    != 0 ? ' tb_pt_' . $layout['padding_top']    : '';
            $classes .= $padding_right            != 0 ? ' tb_pr_' . $padding_right            : '';
            $classes .= $layout['padding_bottom'] != 0 ? ' tb_pb_' . $layout['padding_bottom'] : '';
            $classes .= $padding_left             != 0 ? ' tb_pl_' . $padding_left             : '';
        }

        if (isset($layout['display'])) {
            $classes .= ' display-' . $layout['display'];
        }

        return $classes;
    }

    protected function getBoxClasses()
    {
        $classes  = 'tb_wt';
        $classes .= ' tb_wt_' . $this->getTemplateName();
        $classes .= $this->getDistanceClasses('box');

        if (!empty($this->settings['preset_id'])) {
            $classes .= ' pr_' . $this->settings['preset_id'];
        }

        return $classes;
    }

    protected function getBoxData()
    {
        return '';
    }

    public function getLangSettings($key = null)
    {
        if (!isset($this->settings['lang'])) {
            return array();
        }

        if (isset($this->settings['lang'][$this->language_code])) {
            $lang_data = $this->settings['lang'][$this->language_code];
        } else {
            $lang_data = reset($this->settings['lang']);
        }

        if (null !== $key) {
            return isset($lang_data[$key]) ? $lang_data[$key] : false;
        }

        return $lang_data;
    }

    public function getLangTitle()
    {
        $lang_settings = $this->getLangSettings();

        if (isset($lang_settings['title']) && !empty($lang_settings['title'])) {
            return $lang_settings['title'];
        }

        return '';
    }

    public function getPresentationTitle()
    {
        return $this->getName();
    }

    public function isActive()
    {
        if (isset($this->settings['lang'][$this->language_code]['is_active'])) {
            $result = $this->settings['lang'][$this->language_code]['is_active'];
        } else
        if (isset($this->settings['is_active'])) {
            $result = $this->settings['is_active'];
        } else {
            $result = true;
        }

        return (bool) $result;
    }

    public function render(array $view_data = array())
    {
        $view_data = array_merge($this->getSettings(), $this->getLangSettings(), $view_data);

        $view_data['within_group']      = $this->isWithinGroup();
        $view_data['title_classes']     = $this->getDistanceClasses('title');
        $view_data['theme_widgets_dir'] = $this->engine->getContext()->getCatalogTemplateDir() . '/tb/widget';

        return $this->renderContent(parent::render($view_data));
    }

    protected function isWithinGroup()
    {
        return $this->parent !== null && $this->parent->informChildrenOnRender();
    }

    public function informChildrenOnRender()
    {
        return true;
    }

    protected function renderContent($content)
    {
        $this->has_content = !empty($content);
        $this->is_rendered = true;

        if (!$this->has_content) {
            return '';
        }

        $content = '<div id="' . $this->getDomId() . '" class="' . $this->getBoxClasses() . '"' . $this->getBoxData() . '>' . $content . '</div>';

        if (method_exists($this, 'onRenderWidgetContent')) {
            $content = $this->onRenderWidgetContent($content);
        }

        $this->addAttribute('content', $content);

        return $content;
    }

    protected function getPresetData($preset_id = null)
    {
        static $presets = array();

        if (null == $preset_id && isset($this->settings['preset_id'])) {
            $preset_id = $this->settings['preset_id'];
        }

        if (isset($presets[$preset_id])) {
            return $presets[$preset_id];
        }

        if (!empty($preset_id)) {
            $preset = $this->engine->getSettingsModel('preset', 0)->getScopeSettings($preset_id);

            if (empty($preset)) {
                $preset = $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->getScopeSettings($preset_id);
            }
        }

        if (empty($preset)) {
            $preset = false;
        }

        $presets[$preset_id] = $preset;

        return $preset;
    }
}