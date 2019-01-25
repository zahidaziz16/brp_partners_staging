<?php

class Theme_Admin_ExportModel extends TB_ExtensionModel
{
    public function generate(array $theme_settings, $export_features)
    {
        $export = array();

        if (in_array('settings', $export_features)) {
            $export['theme_settings'] = $theme_settings;

            $export['area_settings'] = array();
            foreach ($this->engine->getStyleSettingsModel()->getValues() as $key => $value) {
                if (!TB_Utils::strEndsWith($key, '__default')) {
                    $export['area_settings'][$key] = $value;
                }
            }
        }

        if (in_array('builder', $export_features)) {
            $export['builder'] = array();

            foreach ($this->engine->getBuilderSettingsModel()->getValues() as $key => $value) {
                if (!TB_Utils::strEndsWith($key, '__default')) {
                    $export['builder'][$key] = $value;
                }
            }

            if ($templates = $this->engine->getSettingsModel('template', 0)->getValues()) {
                $export['templates'] = $templates;
            }
        }

        if (in_array('presets', $export_features)) {
            if ($presets = array_merge($this->engine->getSettingsModel('preset', 0)->getValues(), $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->getValues())) {
                $export['presets'] = $presets;
            }
        }

        if (in_array('menu', $export_features)) {
            if ($menu = $this->engine->getSettingsModel('menu', 0)->getValues()) {
                $export['menu'] = $menu;
            }
        }

        if (in_array('skins', $export_features)) {
            if ($skins = $this->engine->getSettingsModel('skin_custom_' . $this->engine->getThemeId(), 0)->getValues()) {
                $export['skins'] = $skins;
                $export['skins_theme_id'] = $this->engine->getThemeId();
            }
        }

        if (in_array('slider', $export_features)) {
            if ($sliders = $this->engine->getSettingsModel('fire_slider', 0)->getValues()) {
                $export['slider'] = $sliders;
            }
        }

        $export['info'] = array(
            'theme'      => $this->engine->getThemeInfo(),
            'base_url'   => $this->context->getBaseHttp(),
            'oc_version' => $this->context->getOcVersion(),
            'engine'     => $this->context->getEngineConfig(),
            'ip'         => TB_Utils::getIp(),
        );

        return $export;
    }

    public function extractExportImages(array &$export)
    {
        $images = array();

        if (!empty($export['theme_settings']['style']['wrapper'])) {
            $images = array_merge($images, $this->extractStylesImages($export['theme_settings']['style']['wrapper']));
        }

        if (!empty($export['theme_settings']['style']['bottom'])) {
            $images = array_merge($images, $this->extractStylesImages($export['theme_settings']['style']['bottom']));
        }

        if (!empty($export['theme_settings']['background']['global']['image'])) {
            $images[] = $export['theme_settings']['background']['global']['image'];
        }

        if (!empty($export['theme_settings']['background']['page'])) {
            foreach ($export['theme_settings']['background']['page'] as $page_background) {
                if (!empty($page_background['image'])) {
                    $images[] = $page_background['image'];
                }
            }
        }

        if (!empty($export['theme_settings']['background']['category'])) {
            foreach ($export['theme_settings']['background']['category'] as $category_background) {
                if (!empty($category_background['image'])) {
                    $images[] = $category_background['image'];
                }
            }
        }

        if (!empty($export['theme_settings']['payment_images']['rows'])) {
            foreach ($export['theme_settings']['payment_images']['rows'] as $payment_image) {
                if (!empty($payment_image['file'])) {
                    $images[] = $payment_image['file'];
                }
            }
        }

        if (!empty($export['presets']) || !empty($export['theme_presets'])) {
            $presets       = !empty($export['presets']) ? $export['presets'] : array();
            $theme_presets = !empty($export['theme_presets']) ? $export['theme_presets'] : array();

            foreach (array_merge($presets, $theme_presets) as $preset) {
                if (!empty($preset['styles']['box']['background']['rows'])) {
                    $images = array_merge($images, $this->extractStylesImages($preset['styles']['box']));
                }
            }
        }

        if (!empty($export['slider'])) {
            foreach ($export['slider'] as $slider_key => $slider) {
                if (!empty($slider['slides'])) {
                    foreach ($slider['slides'] as $slide) {
                        if (!empty($slide['cover'])) {
                            $images[] = $slide['cover'];
                        }
                        if (!empty($slide['layers'])) {
                            foreach ($slide['layers'] as $layer) {
                                if (!empty($layer['image_src'])) {
                                    $images[] = $layer['image_src'];
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($export['menu'])) {
            foreach ($export['menu'] as $menu) {
                foreach ($menu['tree'] as $language_tree) {
                    foreach ($language_tree as $menu_item) {
                        if ($menu_item['data']['type'] == 'category' && !empty($menu_item['data']['settings']['category_custom_bg'])) {
                            $images[] = $menu_item['data']['settings']['category_custom_bg'];
                        }

                        if ($menu_item['data']['type'] == 'category' && !empty($menu_item['data']['settings']['menu_banner'])) {
                            $images[] = $menu_item['data']['settings']['menu_banner'];
                        }

                        if ($menu_item['data']['type'] == 'html' && !empty($menu_item['data']['settings']['html_text'])) {
                            $images = array_merge($images, $this->extractImagesFromText($menu_item['data']['settings']['html_text']));
                        }

                        if (!empty($menu_item['data']['settings']['menu_icon_image'])) {
                            $images[] = $menu_item['data']['settings']['menu_icon_image'];
                        }

                        if (!empty($menu_item['children'])) {
                            foreach ($menu_item['children'] as $menu_item_child) {
                                if ($menu_item_child['data']['type'] == 'category' && !empty($menu_item_child['data']['settings']['category_custom_bg'])) {
                                    $images[] = $menu_item_child['data']['settings']['category_custom_bg'];
                                }

                                if ($menu_item_child['data']['type'] == 'category' && !empty($menu_item_child['data']['settings']['menu_banner'])) {
                                    $images[] = $menu_item_child['data']['settings']['menu_banner'];
                                }

                                if (!empty($menu_item_child['data']['settings']['menu_icon_image'])) {
                                    $images[] = $menu_item_child['data']['settings']['menu_icon_image'];
                                }

                                if (!empty($menu_item_child['children'])) {
                                    foreach ($menu_item_child['children'] as $menu_item_child_child) {
                                        if (!empty($menu_item_child_child['data']['settings']['menu_icon_image'])) {
                                            $images[] = $menu_item_child_child['data']['settings']['menu_icon_image'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($export['area_settings'])) {
            foreach ($export['area_settings'] as $area_settings) {
                $images = array_merge($images, $this->extractStylesImages($area_settings));
            }
        }

        if (!empty($export['builder'])) {
            foreach ($export['builder'] as $builder_id => &$area) {
                if (empty($area['rows'])) {
                    $area['rows'] = array();
                }

                foreach ($area['rows'] as &$area_row) {
                    $images = array_merge($images, $this->extractStylesImages($area_row['settings']));

                    foreach ($area_row['columns'] as &$area_column) {
                        $images = array_merge($images, $this->extractStylesImages($area_column['settings']));
                        if (!empty($area_column['widgets'])) {
                            foreach ($area_column['widgets'] as &$widget) {
                                if (!empty($widget['settings']['box_styles'])) {
                                    $images = array_merge($images, $this->extractStylesImages($widget['settings']['box_styles']));
                                }
                                if (!empty($widget['settings']['title_styles'])) {
                                    $images = array_merge($images, $this->extractStylesImages($widget['settings']['title_styles']));
                                }

                                $images = array_merge($images, $this->replaceWidgetImages($widget));

                                if (0 === strpos($widget['id'], 'Theme_BannerWidget') && !empty($widget['settings']['image'])) {
                                    $images[] = $widget['settings']['image'];
                                }

                                if (0 === strpos($widget['id'], 'Theme_MenuWidget') && !empty($widget['settings']['separator_image'])) {
                                    $images[] = $widget['settings']['separator_image'];
                                }

                                if (0 === strpos($widget['id'], 'Theme_GalleryWidget') && !empty($widget['settings']['images'])) {
                                    foreach ($widget['settings']['images'] as $gallery_image) {
                                        $images[] = $gallery_image['file'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $images = array_unique($images);
        foreach ($images as $key => $image) {
            if (!file_exists($this->context->getImageDir() . '/' . $image)) {
                unset($images[$key]);
            }
        }

        return $images;
    }

    public function replaceWidgetImages(array &$widget_settings)
    {
        $images  = array();
        $img_dir = $this->engine->gteOc2() ? 'catalog' : 'data';

        if (0 === strpos($widget_settings['id'], 'Theme_TextWidget') || 0 === strpos($widget_settings['id'], 'Theme_CallToActionWidget')) {
            foreach ($widget_settings['settings']['lang'] as &$lang_data) {
                $text_images = $this->extractImagesFromText($lang_data['text']);

                foreach ($text_images as $text_image) {
                    if (file_exists($this->context->getImageDir() . '/' . $text_image)) {
                        $new_text_image = ltrim(substr_replace($text_image, '', 0, strlen($img_dir)), '/');
                        $lang_data['text'] = str_replace($this->context->getImageUrl() . $text_image, '{{image_url}}' . $new_text_image, $lang_data['text']);
                        $images[] = $text_image;
                    }
                }
            }
        }

        if (0 === strpos($widget_settings['id'], 'Theme_IconListWidget')) {
            foreach ($widget_settings['settings']['lang'] as &$icon_list_lang_data) {
                if (!empty($icon_list_lang_data['rows'])) {
                    foreach ($icon_list_lang_data['rows'] as &$icon_list_lang_data_row) {
                        $text_images = $this->extractImagesFromText(html_entity_decode($icon_list_lang_data_row['text'], ENT_COMPAT, 'UTF-8'));

                        foreach ($text_images as $text_image) {
                            if (file_exists($this->context->getImageDir() . '/' . $text_image)) {
                                $new_text_image = ltrim(substr_replace($text_image, '', 0, strlen($img_dir)), '/');
                                $icon_list_lang_data_row['text'] = str_replace($this->context->getImageUrl() . $text_image, '{{image_url}}' . $new_text_image, $icon_list_lang_data_row['text']);
                                $images[] = $text_image;
                            }
                        }
                    }
                }
            }
        }

        if (0 === strpos($widget_settings['id'], 'Theme_GroupWidget') || 0 === strpos($widget_settings['id'], 'Theme_BlockGroupWidget')) {
            if (!empty($widget_settings['subwidgets'])) {
                foreach ($widget_settings['subwidgets'] as &$subwidget) {
                    $images = array_merge($images, $this->replaceWidgetImages($subwidget));
                }
            }
        }

        return $images;
    }

    protected function extractStylesImages($settings)
    {
        $result = array();

        if (!empty($settings['background']['rows'])) {
            foreach ($settings['background']['rows'] as $row) {
                if ($row['background_type'] == 'image' && !empty($row['image'])) {
                    $result[] = $row['image'];
                }
            }
        }

        return $result;
    }

    protected function extractImagesFromText($text)
    {
        $result = array();
        $img_dir = $this->engine->gteOc2() ? 'catalog' : 'data';

        if (preg_match_all('/<img.*?src\s*=.*?>/', $text, $matches)) {
            foreach ($matches[0] as $match) {
                preg_match('@src="([^"]+)"@', $match, $match);
                if (preg_match('/\/image\/' . $img_dir . '\/(.*)/', array_pop($match), $src_match)) {
                    $result[] = $img_dir . '/' . $src_match[1];
                }
            }
        }

        return $result;
    }
}