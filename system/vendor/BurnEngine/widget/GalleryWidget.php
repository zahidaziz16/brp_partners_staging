<?php

class Theme_GalleryWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => '',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        ), $settings));

        $default_grid = array(
            0 => array(
                'max_width'     => 1200,
                'items_per_row' => 5,
                'items_spacing' => 30
            ),
            1 => array(
                'max_width'     => 1000,
                'items_per_row' => 4,
                'items_spacing' => 30
            ),
            2 => array(
                'max_width'     => 750,
                'items_per_row' => 3,
                'items_spacing' => 30
            ),
            3 => array(
                'max_width'     => 450,
                'items_per_row' => 2,
                'items_spacing' => 30
            ),
            4 => array(
                'max_width'     => 300,
                'items_per_row' => 1,
                'items_spacing' => 30
            )
        );

        $settings = array_replace($settings, $this->initFlatVars(array(

            // Gallery navigation
            'nav'                           => true,
            'nav_style'                     => 'thumbs',    // thumbs, dots, numbers
            'nav_position'                  => 'bottom',    // bottom, right, left
            'nav_dots_position'             => 'outside',   // outside, inside
            'nav_spacing'                   => 'md',        // none, 1px, xs, sm, md, lg
            'nav_buttons'                   => 'none',
            'nav_buttons_size'              => 2,           // 1->small; 2->medium; 3->large
            'nav_buttons_visibility'        => 'hover',     // visible, hover
            'nav_thumbs_num'                => 5,

            // Fullscreen gallery
            'fullscreen'                    => true,
            'fullscreen_button_size'        => 'lg',        // md, lg, xxl
            'fullscreen_button_visibility'  => 'visible',   // visible, hover
            'fullscreen_button_position'    => 'tr',        // tr -> top right; br -> bottom right; bl -> bottom left; tl -> top left
            'fullscreen_button_icon'        => 'fa-expand',
            'fullscreen_button_icon_size'   => '20',
            'fullscreen_thumbs'             => false,

            // Caption
            'caption_position'              => 'bottom',    // top, bottom
            'caption_opacity'               => 0.2,

            // Grid gallery
            'restrictions'      => $default_grid,

            // Gallery type
            'images'            => array(),
            'gallery_type'      => 'slide', // slide, grid
            'thumb_gutter'      => 1, // px
            'filter_randomize'  => 0,
            'thumb_width'       => 100,
            'thumb_height'      => 100,
            'slide_width'       => 300,
            'slide_height'      => 300,
            'full_width'        => 1200,
            'full_height'       => 800,
            'crop_thumbs'       => 1
        ), $settings));

        foreach ($settings['restrictions'] as $i => $row) {
            if (empty($row['max_width']) || empty($row['items_per_row'])) {
                unset($settings['restrictions'][$i]);
            }
        }

        if (empty($settings['restrictions'])) {
            $settings['restrictions'] = $default_grid;
        }
    }

    public function onEdit(array &$settings)
    {
        if (isset($settings['images']) && !empty($settings['images'])) {
            foreach ($settings['images'] as &$image) {
                if (!empty($image['file'])  && file_exists(DIR_IMAGE . $image['file'])) {
                    $image['preview'] = $this->getOcModel('tool/image')->resize($image['file'], 100, 100);
                } else {
                    $image['preview'] = $this->getThemeModel()->getNoImage();
                }
            }
        }
    }

    public function onTransformSettings(array &$settings)
    {
        if (isset($settings['images']) && !empty($settings['images'])) {
            foreach ($settings['images'] as $key => &$image) {
                if (empty($image['file'])  || !file_exists(DIR_IMAGE . $image['file'])) {
                    unset($settings['images'][$key]);
                } else {
                    list($width, $height) = getimagesize(DIR_IMAGE . $image['file']);
                    $image['width'] = $width;
                    $image['height'] = $height;
                }
            }
        }
    }

    public function initFrontend()
    {
        if ($this->settings['fullscreen'] || $this->settings['gallery_type'] == 'grid') {
            $this->themeData->registerStylesheetResource('javascript/photoswipe/photoswipe.css');
            $this->themeData->registerStylesheetResource('javascript/photoswipe/default-skin/default-skin.css', null, array('url(' => 'url(' . $this->engine->getContext()->getThemeCatalogResourceUrl() . 'javascript/photoswipe/default-skin/'));
            $this->themeData->registerJavascriptResource('javascript/photoswipe/photoswipe.min.js');
            $this->themeData->registerJavascriptResource('javascript/photoswipe/photoswipe-ui-default.min.js');
        }

        $this->engine->getExtension('fire_slider')->registerMightySlider();
        $this->engine->getExtension('fire_slider')->registerStylesheetResources();
        foreach($this->settings['images'] as $image) {
            if (isset($image['lang'][$this->language_code]['caption'])) {
                $this->engine->getExtension('fire_slider')->registerTweenlite();
            }
        }
    }

    public function render(array $view_data = array())
    {
        $settings  = $this->settings;
        $font_base = $this->themeData->fonts['body']['line-height'];

        $gallery_css_classes  = '';
        $gallery_css_classes .= ' tb_thumbs_position_' . $settings['nav_position'];
        $gallery_css_classes .= $settings['nav_style'] == 'thumbs' ? $settings['nav_spacing'] != 'none' ? ' tb_thumbs_spacing_'  . $settings['nav_spacing'] : '' : '';
        $gallery_css_classes .= $settings['nav_style'] == 'thumbs' ? $settings['nav_position'] != 'bottom' ? ' tb_thumbs_vertical' : ' tb_thumbs_horizontal' : '';
        $gallery_css_classes .= $settings['crop_thumbs'] ? ' tb_thumbs_crop' : '';
        $gallery_css_classes .= $settings['nav_style'] == 'dots' && $settings['nav_dots_position'] == 'outside' ? ' tb_dots_outside' : '';
        $gallery_css_classes .= $settings['nav_buttons'] ? ' tb_nav_size_' . $settings['nav_buttons_size'] : '';
        $gallery_css_classes .= $settings['nav_buttons'] && $settings['nav_buttons_visibility'] != 'visible' ? ' tb_nav_visibility_' . $settings['nav_buttons_visibility'] : '';
        $gallery_css_classes .= $settings['fullscreen'] ? $settings['fullscreen_button_visibility'] != 'visible' ? ' tb_fullscreen_button_hover' : '' : '';
        $gallery_css_classes .= $settings['fullscreen'] ? ' tb_fullscreen_button_position_' . $settings['fullscreen_button_position'] : '';


        $images = array();
        $images_js_array = '';
        $method = $this->settings['crop_thumbs'] ? 'crop' : 'fit';
        $img_url = $this->engine->getContext()->getImageUrl();

        $i = 0;
        foreach ($settings['images'] as $image) {

            if ($settings['crop_thumbs']) {
                $thumb = $this->getThemeModel()->resizeImageAdvanced($image['file'], $settings['thumb_width'], $settings['thumb_height'], $method);
            } else {
                if ($settings['nav_position'] == 'bottom') {
                    $thumb = $this->getThemeModel()->resizeImageAdvanced($image['file'], 'auto', $settings['thumb_height'], $method);
                } else {
                    $thumb = $this->getThemeModel()->resizeImageAdvanced($image['file'], $settings['thumb_width'], 'auto', $method);
                }
            }

            if (empty($thumb)) {
                continue;
            }

            $slide   = $this->getThemeModel()->resizeImageAdvanced($image['file'], $settings['crop_thumbs'] ? $settings['slide_width'] : 'auto', $settings['slide_height'], $method);
            if ($settings['gallery_type'] == 'grid') {
                $preview = $this->getThemeModel()->resizeImageAdvanced($image['file'], $settings['thumb_width'], $settings['thumb_height']);
            } else {
                $preview = $this->getThemeModel()->resizeImageAdvanced($image['file'], $settings['slide_width'], $settings['slide_height']);
            }
            $full = $this->getThemeModel()->resizeImageAdvanced($image['file'], $settings['full_width'], $settings['full_height']);
            $link    = !empty($image['lang'][$this->language_code]['url']) ? $image['lang'][$this->language_code]['url'] : '';
            $target  = !empty($image['lang'][$this->language_code]['url_target']) ? $image['lang'][$this->language_code]['url_target'] : '_self';
            $caption = isset($image['lang'][$this->language_code]['caption']) ? $image['lang'][$this->language_code]['caption'] : '';

            list($thumb_width, $thumb_height)     = getimagesize(DIR_IMAGE . $thumb);
            list($slide_width, $slide_height)     = getimagesize(DIR_IMAGE . $slide);
            list($preview_width, $preview_height) = getimagesize(DIR_IMAGE . $preview);
            list($full_width, $full_height)       = getimagesize(DIR_IMAGE . $full);

            $images[] = array(
                'thumb'      => array(
                    'src'       => $img_url . TB_Utils::escapeHtmlImage($thumb),
                    'width'     => $thumb_width,
                    'height'    => $thumb_height
                ),
                'slide'      => array(
                    'src'       => $img_url . TB_Utils::escapeHtmlImage($slide),
                    'width'     => $slide_width,
                    'height'    => $slide_height
                ),
                'preview'    => array(
                    'src'       => $img_url . TB_Utils::escapeHtmlImage($preview),
                    'width'     => $preview_width,
                    'height'    => $preview_height
                ),
                'full'       => array(
                    'src'       => $img_url . TB_Utils::escapeHtmlImage($full),
                    'width'     => $full_width,
                    'height'    => $full_height
                ),
                'link'       => array(
                    'url'        => $link,
                    'url_target' => $target,
                ),
                'caption'    => $caption
            );

            $images_js_array .= '{
                src:  \'' . $img_url . TB_Utils::escapeHtmlImage($full) . '\',
                w:      ' . $full_width . ',
                h:      ' . $full_height . ',
                msrc: \'' . $img_url . TB_Utils::escapeHtmlImage($preview) . '\',
                title: \''. $caption . '\'
            }, ';

            $i++;
        }

        if (empty($images)) {
            return '';
        }

        $padding  = 0;
        $padding += $settings['nav_spacing'] == 'xs' ? $font_base * 0.25 : 0;
        $padding += $settings['nav_spacing'] == 'sm' ? $font_base * 0.5  : 0;
        $padding += $settings['nav_spacing'] == 'md' ? $font_base        : 0;
        $padding += $settings['nav_spacing'] == 'lg' ? $font_base * 1.5  : 0;

        $h1 = $settings['slide_height'] / $settings['slide_width'] * 100;
        $r1 = $settings['slide_width'] / $settings['slide_height'];
        $r2 = $settings['thumb_width'] / $settings['thumb_height'];
        $n  = $settings['nav_thumbs_num'];

        if ($images > 1 && $settings['nav_position'] == 'bottom') {
            if ($settings['crop_thumbs']) {
                $ratio_plus  = 'calc('  . ($h1 + (100 / ($n * $r2))) . '% + ' . ($padding - ($padding * ($n - 1) / ($r2 * $n))) . 'px)';
                $ratio_minus = 'calc(-' . ($h1 + (100 / ($n * $r2))) . '% - ' . ($padding - ($padding * ($n - 1) / ($r2 * $n))) . 'px)';
            }
            else {
                $ratio_plus  = 'calc('  . $h1 . '% + ' . ($settings['thumb_height'] + $padding) . 'px)';
                $ratio_minus = 'calc(-' . $h1 . '% - ' . ($settings['thumb_height'] + $padding) . 'px)';
            }
        } else {
            $ratio_minus = '';

            if ($settings['crop_thumbs']) {
                $ratio_plus  = 'calc(' . ((100 * $n) / ($r1 * $n + $r2)) . '% + ' . (($padding * $r2 * $n - $padding * $r2 - $padding * $n) / ($r1 * $n + $r2)) . 'px)';
            } else {
                $ratio_plus  = 'calc(' . (100 / $r1) . '% - ' . (($settings['thumb_width'] + $padding) / $r1) . 'px)';
            }
        }

        if ($settings['nav_style'] != 'thumbs') {
            $ratio_plus  =  $h1 . '%';
            $ratio_minus = -$h1 . '%';
        }

        $restrictions_json = array();

        if ($settings['gallery_type'] == 'grid') {
            foreach ($this->settings['restrictions'] as $restriction) {
                $restrictions_json[$restriction['max_width']] = array(
                    'items_per_row' => $restriction['items_per_row'],
                    'items_spacing' => 0
                );
            }
            krsort($restrictions_json);
        }

        $view_data['restrictions_json']   = json_encode($restrictions_json);
        $view_data['gallery_css_classes'] = $gallery_css_classes;
        $view_data['padding']             = $padding;
        $view_data['images']              = $images;
        $view_data['images_array']        = '[ ' . trim($images_js_array, ', ') . ' ]';
        $view_data['url']                 = $this->engine->getOcUrl();
        $view_data['ratio_plus']          = $ratio_plus;
        $view_data['ratio_minus']         = $ratio_minus;
        $view_data['w1']                  = 100 / $r1;

        return parent::render($view_data);
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $font_base = $this->themeData->fonts['body']['line-height'];
        $settings  = $this->settings;
        $padding   = 0;
        $padding  += $settings['nav_spacing'] == 'xs' ? $font_base * 0.25 : 0;
        $padding  += $settings['nav_spacing'] == 'sm' ? $font_base * 0.5  : 0;
        $padding  += $settings['nav_spacing'] == 'md' ? $font_base        : 0;
        $padding  += $settings['nav_spacing'] == 'lg' ? $font_base * 1.5  : 0;

        $w1 = $settings['slide_width'];
        $h1 = $settings['slide_height'];
        $w2 = $settings['thumb_width'];
        $h2 = $settings['thumb_height'];
        $r1 = $w1 / $h1;
        $r2 = $w2 / $h2;
        $n  = $settings['nav_thumbs_num'];

        if ($settings['gallery_type'] == 'slide') {
            $css  = '#' . $this->getDomId() . ' .mightySlider .frame {';
            $css .= '  height: ' . $settings['slide_height'] . 'px;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' .tb_caption {';
            $css .= '  ' . $settings['caption_position'] . ': 0';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' .tb_caption:after {';
            $css .= '  opacity: ' . $settings['caption_opacity'] . ';';
            $css .= '}';

            if ($settings['nav_style'] == 'thumbs') {
                if ($settings['nav_position'] != 'bottom') {
                    if ($settings['crop_thumbs']) {
                        $css .= '#' . $this->getDomId() . ' .tb_slides {';
                        $css .= '  width: calc(' . (((100 * $n) / ($r1 * $n + $r2)) * $r1) . '% + ' . ((($padding * $r2 * $n - $padding * $r2 - $padding * $n) / ($r1 * $n + $r2)) * $r1) . 'px);';
                        $css .= '}';
                        $css .= '#' . $this->getDomId() . ' .tb_thumbs_wrap {';
                        $css .= '  width: calc(' . (100 - ((100 * $n) / ($r1 * $n + $r2)) * $r1) . '% - ' . ($padding + (($padding * $r2 * $n - $padding * $r2 - $padding * $n) / ($r1 * $n + $r2)) * $r1) . 'px);';
                        $css .= '}';
                    } else {
                        $css .= '#' . $this->getDomId() . ' .tb_slides {';
                        $css .= '  width: calc(100% - ' . ($padding + $w2) . 'px);';
                        $css .= '}';
                        $css .= '#' . $this->getDomId() . ' .tb_thumbs_wrap {';
                        $css .= '  width: ' . $w2 . 'px';
                        $css .= '}';
                    }
                } else {
                    if ($settings['crop_thumbs']) {
                        $css .= '#' . $this->getDomId() . ' .tb_thumbs_wrap {';
                        $css .= '  padding-top: calc('  . (100 / ($n * $r2)) . '% - ' . (($padding / $r2) - ($padding / ($n * $r2))) . 'px);';
                        $css .= '}';
                    }
                }
            }

            $styleBuilder->addCss($css);
        } else {
            $css  = '#' . $this->getDomId() . ' .tb_gallery.tb_grid_view {';
            $css .= '  margin-left: -' . $settings['thumb_gutter'] . 'px;';
            $css .= '  margin-top:  -' . $settings['thumb_gutter'] . 'px;';
            $css .= '}';
            $css .= '#' . $this->getDomId() . ' .tb_gallery.tb_grid_view > * {';
            $css .= '  padding-left: ' . $settings['thumb_gutter'] . 'px;';
            $css .= '  padding-top:  ' . $settings['thumb_gutter'] . 'px;';
            $css .= '}';

            $styleBuilder->addCss($css);
        }
    }

    protected function getBoxClasses()
    {
        $lazyload  = $this->themeData->system['js_lazyload'];
        $classes   = parent::getBoxClasses();
        $classes  .= $lazyload ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $lazyload  = $this->themeData->system['js_lazyload'];
        $data      = parent::getBoxData();
        $data     .= $lazyload ? ' data="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

        return $data;
    }

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'gallery_navigation' => array(
                '_label' => 'Gallery Prev/Next buttons',
                'button_default' => array(
                    'label'       => 'Text',
                    'elements'    => '.mSButtons:not(:hover) svg',
                    'property'    => 'fill',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_default'
                ),
                'button_bg_default' => array(
                    'label'       => 'Background',
                    'elements'    => '.mSButtons:not(:hover):after',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_bg_default'
                ),
                'button_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '.mSButtons:hover svg',
                    'property'    => 'fill',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_hover'
                ),
                'button_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '.mSButtons:hover:after',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_navigation.button_bg_hover'
                )
            ),
            'gallery_pagination' => array(
                '_label' => 'Gallery Pagination',
                'default' => array(
                    'label'       => 'Text',
                    'elements'    => '.mSPages li:not(:hover):not(.active)',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_pagination.default'
                ),
                'hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '.mSPages li:hover',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_pagination.hover'
                ),
                'active' => array(
                    'label'       => 'Text (active)',
                    'elements'    => '.mSPages li.active, .mSPages li.active:hover',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_pagination.active'
                )
            ),
            'gallery_fullscreen_button' => array(
                '_label' => '"Go fullscreen" button',
                'fbutton_default' => array(
                    'label'       => 'Text',
                    'elements'    => '.tb_fullscreen_button',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_fullscreen_button.fbutton_default'
                ),
                'fbutton_bg_default' => array(
                    'label'       => 'Background',
                    'elements'    => '.tb_fullscreen_button',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_fullscreen_button.fbutton_bg_default'
                ),
                'fbutton_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => '.tb_fullscreen_button:hover',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 1,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_fullscreen_button.fbutton_hover'
                ),
                'fbutton_bg_hover' => array(
                    'label'       => 'Background (hover)',
                    'elements'    => '.tb_fullscreen_button:hover',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_fullscreen_button.fbutton_bg_hover'
                ),
            ),
            'gallery_caption' => array(
                '_label' => 'Caption',
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '.tb_caption .tb_text',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_caption.text'
                ),
                'bg' => array(
                    'label'       => 'Background',
                    'elements'    => '.tb_caption:after',
                    'property'    => 'background-color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'theme:gallery_caption.bg'
                ),
            ),
        );

        return $default_colors;
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'caption' => array(
                'section_name'      => 'Caption',
                'elements'          => '.tb_caption .tb_text',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 16,
                'line-height'       => 20,
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => true,
                'has_effects'       => true,
                'show_built_styles' => true,
                'multiple_variants' => false,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
        );
    }
}