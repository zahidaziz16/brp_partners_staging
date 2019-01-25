<?php

class Theme_Admin_WidgetController extends TB_AdminController
{
    /**
     * @var Theme_Admin_LayoutBuilderModel
     */
    protected $layoutBuilderModel;

    public function init()
    {
        $this->layoutBuilderModel = $this->getModel('layoutBuilder');
    }

    public function createForm()
    {
        if (!isset($this->request->post['settings']) || empty($this->request->get['class_name']) || empty($this->request->post['column_id'])) {
            return $this->sendJsonError('Invalid arguments. Cannot create widget');
        }

        $widget_settings = unserialize(gzuncompress(base64_decode((string) $this->request->post['settings'])));
        if (!is_array($widget_settings)) {
            return $this->sendJsonError('Cannot create settings form. Invalid settings data.');
        }

        $args = $this->getAreaArgs();
        if (null === $args) {
            return $this->sendJsonError('Invalid arguments');
        }

        $column_id = $this->request->post['column_id'];
        $column_settings = array();
        if (!empty($this->request->post['column_settings'])) {
            foreach (unserialize(gzuncompress(base64_decode((string) $this->request->post['column_settings']))) as $column_settings_id => $column_settings_item) {
                if ($column_settings_id == $column_id) {
                    $column_settings = $column_settings_item;
                    break;
                }
            }
        }

        list ($area_name, $area_type, $area_id) = $args;

        $class_name    = (string) $this->request->get['class_name'];
        $area_settings = $this->getAreaSettings($area_name, $area_type, $area_id);
        $row_settings  = unserialize(gzuncompress(base64_decode((string) $this->request->post['row_settings'])));
        $theme_colors  = json_decode(html_entity_decode((string) $this->request->post['theme_colors'], ENT_COMPAT, 'UTF-8'), true);

        $this->layoutBuilderModel->filterRowSettings($row_settings, 'area_' . $area_name, $area_settings, $theme_colors['colors']);
        $this->layoutBuilderModel->filterColumnSettings($column_settings, $row_settings);

        $selected_preset_id = "";
        if (!empty($this->request->post['preset_id'])) {
            $selected_preset_id = (string) $this->request->post['preset_id'];
        }

        $apply_preset = false;
        if (isset($this->request->post['apply_preset'])) {
            $apply_preset = (bool) $this->request->post['apply_preset'];
        }

        $widget_preset_id = "";
        if (!empty($widget_settings['preset_id']) && empty($selected_preset_id)) {
            $widget_preset_id = $widget_settings['preset_id'];
            $selected_preset_id = $widget_preset_id;
        } else
        if ($apply_preset && !empty($selected_preset_id)) {
            $widget_preset_id = $selected_preset_id;
        }

        $current_preset = array();
        $preset_options = array();
        foreach (array_merge($this->engine->getSettingsModel('preset', 0)->getValues(), $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->getValues()) as $preset) {
            $preset_options[$preset['id']] = $preset['name'];
            if ($preset['id'] == $selected_preset_id || $preset['id'] == $widget_preset_id) {
                $current_preset = $preset;
            }
        }

        if (!empty($widget_preset_id) && empty($current_preset)) {
            $widget_preset_id = "";
        }

        $preset_box_color_keys = !empty($current_preset['styles']['box']['colors']) ? array_keys($current_preset['styles']['box']['colors']) : array();
        $preset_box_font_keys  = !empty($current_preset['styles']['box']['font'])   ? array_keys(reset($current_preset['styles']['box']['font'])) : array();

        /*
        if (!empty($current_preset)) {

            if (!empty($preset_box_font_keys)) {
                foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
                    if (!isset($current_preset['styles']['box']['font'][$language_code])) {
                        $current_preset['styles']['box']['font'][$language_code] = reset($current_preset['styles']['box']['font']);
                    }
                }
            }

            foreach ($current_preset['styles'] as $style_section => $preset_style_settings) {
                if (!isset($widget_settings[$style_section . '_styles'])) {
                    continue;
                }

                $widget_settings[$style_section . '_styles'] = array_replace_recursive($widget_settings[$style_section . '_styles'], $preset_style_settings);

                $colors = $widget->{'getDefault' . $style_section . 'Colors'}();
                $preset_color_keys = !empty($preset_style_settings['colors']) ? array_keys(array_intersect_key($preset_style_settings['colors'], $colors)) : array();

                if ($style_section == 'box' && $preset_color_keys) {
                    $preset_box_color_keys = $preset_color_keys;

                    foreach (array_keys($colors) as $color_group_key) {
                        if (!in_array($color_group_key, $preset_color_keys) && isset($widget_settings['box_styles']['colors'][$color_group_key])) {
                            $colors[$color_group_key] = array_replace_recursive($colors[$color_group_key], $widget_settings['box_styles']['colors'][$color_group_key]);
                        }
                    }
                }

                foreach ($colors as $color_group_key => &$color_sections) {
                    if (isset($preset_style_settings['colors'][$color_group_key])) {
                        $color_sections = array_replace_recursive($color_sections, array_intersect_key($preset_style_settings['colors'][$color_group_key], $color_sections));
                        foreach ($color_sections as $color_section_key => &$color_section_values) {
                            if ($color_section_key != "_label") {
                                $color_section_values['inherit'] = 0;
                            }
                        }

                        $widget_settings[$style_section . '_styles']['colors'][$color_group_key] = $color_sections;
                    }
                }


                $default_fonts = $widget->{'getDefault' . $style_section . 'Fonts'}();
                $preset_font_keys = !empty($preset_style_settings['font']) ? array_keys(array_intersect_key(reset($preset_style_settings['font']), $default_fonts)) : array();

                if ($style_section == 'box' && $preset_font_keys) {
                    $preset_box_font_keys = $preset_font_keys;

                    foreach ($widget_settings['box_styles']['font'] as &$settings_fonts) {
                        $settings_fonts = array_intersect_key($settings_fonts, $default_fonts);

                        foreach (array_keys($default_fonts) as $font_group_key) {
                            if (!in_array($font_group_key, $preset_font_keys) && isset($settings_fonts[$font_group_key])) {
                                $settings_fonts[$font_group_key] = array_replace_recursive($default_fonts[$font_group_key], $settings_fonts[$font_group_key]);
                            }
                        }
                    }
                }
            }
        }
        */

        $widget = $this->engine->getWidgetManager()->createFilterAndEditWidget($class_name, $widget_settings);

        $this->data['preset_box_color_keys'] = $preset_box_color_keys;
        $this->data['preset_box_font_keys']  = $preset_box_font_keys;
        $this->data['preset_options']        = $preset_options;
        $this->data['selected_preset_id']    = $selected_preset_id;
        $this->data['widget_preset_id']      = $widget_preset_id;
        $this->data['widget']                = $widget;
        $this->data['settings']              = $widget->getSettings();
        $this->data['core_widgets_dir']      = $this->context->getEngineAreaTemplateDir() . '/widget';

        $this->renderTemplate($widget->getExtension()->getAreaDir() . '/view/template/widget/' . $widget->getTemplateName() . '.tpl');
    }

    public function convertFormDataToSettings()
    {
        if (!isset($this->request->post['widget_data']) || !isset($this->request->get['class_name'])) {
            return $this->sendJsonError('Invalid arguments. Cannot create widget');
        }

        $args = $this->getAreaArgs();
        if (null === $args) {
            return $this->sendJsonError('Invalid arguments');
        }

        list ($area_name, $area_type, $area_id) = $args;

        $class_name      = (string) $this->request->get['class_name'];
        $widget_settings = json_decode(html_entity_decode((string) $this->request->post['widget_data'], ENT_COMPAT, 'UTF-8'), true);

        $area_settings   = $this->getAreaSettings($area_name, $area_type, $area_id);
        $row_settings    = unserialize(gzuncompress(base64_decode((string) $this->request->post['row_settings'])));
        $theme_colors    = json_decode(html_entity_decode((string) $this->request->post['theme_colors'], ENT_COMPAT, 'UTF-8'), true);

        $this->layoutBuilderModel->filterRowSettings($row_settings, 'area_' . $area_name, $area_settings, $theme_colors['colors']);

        $widget = $this->engine->getWidgetManager()->createTransformAndFilterWidget($class_name, $widget_settings);

        $result = array(
            'title' => $widget->getPresentationTitle(),
            'data'  => $widget->getSettingsEncoded()
        );

        $this->setOutput(json_encode($result));
    }

    public function saveToFavourites()
    {
        if (!isset($this->request->post['widget_data']) || !isset($this->request->post['widget_id'])) {
            return $this->sendJsonError('Invalid arguments. Cannot create widget');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $widget_id       = (string) $this->request->post['widget_id'];
        $widget_settings = unserialize(gzuncompress(base64_decode((string) $this->request->post['widget_data'])));

        $favourites = $this->engine->getSettingsModel('favourites', 0)->getScopeSettings('widgets');
        $favourites[$widget_id] = $widget_settings;

        $this->engine->getSettingsModel('favourites', 0)->persistCustomSettings($favourites, 'widgets');
    }

    public function removeFromFavourites()
    {
        if (!isset($this->request->get['widget_id'])) {
            return $this->sendJsonError('Invalid arguments. Cannot create widget');
        }

        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $favourites = $this->engine->getSettingsModel('favourites', 0)->getScopeSettings('widgets');
        unset($favourites[(string) $this->request->get['widget_id']]);

        $this->engine->getSettingsModel('favourites', 0)->persistCustomSettings($favourites, 'widgets');
    }

    protected function getAreaSettings($area_name, $area_type, $area_id)
    {
        if (!empty($this->request->post['area_settings'])) {
            $area_settings = json_decode(html_entity_decode((string) $this->request->post['area_settings'], ENT_COMPAT, 'UTF-8'), true);
            $area_settings = $area_settings['area'][$area_name];
        } else {
            $area_key = strpos($area_id, $area_type) !== 0 ? $area_type . '_' . $area_id : $area_id;
            $area_settings = $this->engine->getWidgetManager()->getAreaStyle($area_name . '_' . $area_key);

            if (empty($area_settings)) {
                try {
                    $params = $this->layoutBuilderModel->determineAreaParams($area_name, $area_type, $area_id, 'style');
                } catch (Exception $e) {
                    return $this->sendJsonError($e->getMessage());
                }

                list(, $area_key) = $params;
                $area_settings = $this->engine->getWidgetManager()->getAreaStyle($area_name . '_' . $area_key, 'style');
            }

            if (empty($area_settings)) {
                $area_settings = array();
            }
        }

        return $area_settings;
    }

    protected function getAreaArgs()
    {
        $area_name = null;
        if (isset($this->request->request['area_name'])) {
            $name = (string) $this->request->request['area_name'];
            if (in_array((string) $name, array('header', 'footer', 'content', 'intro', 'column_left', 'column_right'))) {
                $area_name = $name;
            }
        }

        $area_type = null;
        if (isset($this->request->request['area_type'])) {
            $type = (string) $this->request->request['area_type'];
            if (in_array($type, array('global', 'home', 'page', 'category', 'product', 'layout', 'system', 'quickview'))) {
                $area_type = $type;
            }
        }

        $area_id = null;
        if (isset($this->request->request['area_id'])) {
            $area_id = (string) $this->request->request['area_id'];
        }

        if ($area_name == null || $area_type == null || $area_id == null) {
            return null;
        }

        return array($area_name, $area_type, $area_id);
    }
}