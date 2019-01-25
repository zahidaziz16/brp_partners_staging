<?php
class FireSlider_Admin_DefaultModel extends TB_ExtensionModel
{
    public function initSlider(array &$slider)
    {
        $default_settings = array(
            'id'                      => TB_Utils::genRandomString(),
            'name'                    => 'Slider ' . TB_Utils::randName(7),
            'layout'                  => 'fixed',
            'header_style'            => 'visible',
            'transparent_header'      => 1,
            'width'                   => 1000,
            'height'                  => 400,
            'responsive_width'        => 1000,
            'responsive_height'       => 400,
            'scene_width'             => 1000,
            'scene_height'            => 400,
            'max_height'              => 1000,
            'min_height'              => 250,
            'offset_top'              => 50,
            'offset_left'             => 50,
            'sizes'                   => array(),
            'speed'                   => 500,
            'easing'                  => 'easeOutQuad',
            'random'                  => 0,
            'viewport'                => 'fill',
            'autoscale'               => 0,
            'autoplay'                => 0,
            'hover_pause'             => 1,
            'pause_time'              => 5000,
            'custom_css'              => '',
            'slides'                  => array()
        );

        $slider = TB_FormHelper::initFlatVarsSimple($default_settings, $slider);
    }

    public function filterSliderForView(array &$slider)
    {
        foreach ($slider['slides'] as &$slide) {
            $slide['cover'] = html_entity_decode($slide['cover'], ENT_QUOTES, 'UTF-8');

            if (!empty($slide['cover']) && file_exists(DIR_IMAGE . $slide['cover'])) {
                $slide['cover_image_preview'] = $this->getOcModel('tool/image')->resize($slide['cover'], 100, 100);
            } else {
                $slide['cover_image_preview'] = $this->getThemeModel()->getNoImage();
            }

            if (!isset($slide['layers'])) {
                $slide['layers'] = array();
            } else {
                foreach ($slide['layers'] as &$layer) {
                    if (!empty($layer['image_src'])  && file_exists(DIR_IMAGE . $layer['image_src'])) {
                        $layer['image_preview'] = $this->getOcModel('tool/image')->resize($layer['image_src'], 100, 100);
                    } else {
                        $layer['image_preview'] = $this->getThemeModel()->getNoImage();
                    }
                }
            }
        }
    }

    public function persistSlider($slider)
    {
        foreach ($slider['slides'] as &$slide) {
            $slide['layers'] = json_decode(html_entity_decode(trim($slide['layers']), ENT_QUOTES, 'UTF-8'), true);
        }

        if (!$this->getThemeModel()->keyExists((string) $slider['id'], 'fire_slider', 0)) {
            $slider['id'] = TB_Utils::slugify($slider['name']) . '-' . (string) $slider['id'];
        }

        $sliderSettingsModel = $this->engine->getSettingsModel('fire_slider', 0);
        $sliderSettingsModel->setAndPersistScopeSettings((string) $slider['id'], $slider);

        $this->engine->wipeVarsCache('fireslider_' . $slider['id'] . '*');
        $this->engine->wipeStylesCache();
    }
}