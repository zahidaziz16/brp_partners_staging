<?php

require_once 'SystemWidget.php';

class Theme_ProductImagesSystemWidget extends Theme_SystemWidget
{
    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initFlatVars(array(
            'nav'                           => true,
            'nav_style'                     => 'thumbs',    // thumbs, dots, numbers
            'nav_position'                  => 'bottom',    // bottom, right, left
            'nav_dots_position'             => 'outside',   // outside, inside
            'nav_spacing'                   => 'md',        // none, 1px, xs, sm, md, lg
            'nav_buttons'                   => 'none',
            'nav_buttons_size'              => 2,           // 1->small; 2->medium; 3->large
            'nav_buttons_visibility'        => 'hover',     // visible, hover
            'nav_thumbs_num'                => 5,
            'zoom'                          => true,
            'zoom_trigger'                  => 'click',     // click, mouseover, grab
            'fullscreen'                    => true,
            'fullscreen_button_size'        => 'lg',        // md, lg, xxl
            'fullscreen_button_visibility'  => 'visible',   // visible, hover
            'fullscreen_button_position'    => 'tr',        // tr -> top right; br -> bottom right; bl -> bottom left; tl -> top left
            'fullscreen_button_icon'        => 'fa-expand',
            'fullscreen_button_icon_size'   => '20',
            'fullscreen_thumbs'             => false
        ), $settings));
    }

    public function onRenderWidgetContent($content)
    {
        $font_base = $this->themeData->fonts['body']['line-height'];
        $settings = $this->getSettings();
        $padding   = 0;
        $padding  += $settings['nav_spacing'] == 'xs' ? $font_base * 0.25 : 0;
        $padding  += $settings['nav_spacing'] == 'sm' ? $font_base * 0.5  : 0;
        $padding  += $settings['nav_spacing'] == 'md' ? $font_base        : 0;
        $padding  += $settings['nav_spacing'] == 'lg' ? $font_base * 1.5  : 0;

        $config_group = $this->engine->gteOc22() ? TB_Engine::getName() : 'config';

        $w1 = $this->themeData['config']->get($config_group . '_image_thumb_width');
        $h1 = $this->themeData['config']->get($config_group . '_image_thumb_height');
        $w2 = $this->themeData['config']->get($config_group . '_image_additional_width');
        $h2 = $this->themeData['config']->get($config_group . '_image_additional_height');
        $r1 = $w1 / $h1;
        $r2 = $w2 / $h2;
        $n  = $settings['nav_thumbs_num'];

        if ($settings['nav_position'] == 'bottom') {
            $ratio_plus   = 'calc('  . (($h1 / $w1) * 100 + (100 / ($n * $r2))) . '% + ' . ($padding - ($padding * ($n - 1) / ($r2 * $n))) . 'px)';
            $ratio_minus  = 'calc(-' . (($h1 / $w1) * 100 + (100 / ($n * $r2))) . '% - ' . ($padding - ($padding * ($n - 1) / ($r2 * $n))) . 'px)';
            $ratio_thumbs = 'calc('  . (100 / ($n * $r2)) . '% - ' . (($padding / $r2) - ($padding / ($n * $r2))) . 'px)';
        } else {
            $ratio_minus  = '';
            $ratio_plus   = 'calc(' . ((100 * $n) / ($r1 * $n + $r2)) . '% + ' . (($padding * $r2 * $n - $padding * $r2 - $padding * $n) / ($r1 * $n + $r2)) . 'px)';
            $ratio_thumbs = '';
        }

        if ($settings['nav_style'] != 'thumbs') {
            $ratio_plus  =  ($h1 / $w1 * 100) . '%';
            $ratio_minus = -($h1 / $w1 * 100) . '%';
        }

        $ratio_single = ($h1 / $w1) * 100;

        $gallery_css_classes  = '';
        $gallery_css_classes .= ' tb_thumbs_position_' . $settings['nav_position'];
        $gallery_css_classes .= $settings['nav_style'] == 'thumbs' ? $settings['nav_spacing'] != 'none' ? ' tb_thumbs_spacing_'  . $settings['nav_spacing'] : '' : '';
        $gallery_css_classes .= $settings['nav_style'] == 'thumbs' ? $settings['nav_position'] != 'bottom' ? ' tb_thumbs_vertical' : ' tb_thumbs_horizontal' : '';
        $gallery_css_classes .= $settings['nav_style'] == 'dots' && $settings['nav_dots_position'] == 'outside' ? ' tb_dots_outside' : '';
        $gallery_css_classes .= ' tb_thumbs_crop';
        $gallery_css_classes .= $settings['nav_buttons'] ? ' tb_nav_size_' . $settings['nav_buttons_size'] : '';
        $gallery_css_classes .= $settings['nav_buttons'] && $settings['nav_buttons_visibility'] != 'visible' ? ' tb_nav_visibility_' . $settings['nav_buttons_visibility'] : '';
        $gallery_css_classes .= $settings['fullscreen'] ? $settings['fullscreen_button_visibility'] != 'visible' ? ' tb_fullscreen_button_hover' : '' : '';
        $gallery_css_classes .= $settings['fullscreen'] ? ' tb_fullscreen_button_position_' . $settings['fullscreen_button_position'] : '';

        $content = str_replace(
            array(
                '{{gallery_css_classes}}',
                '{{ratio_plus}}',
                '{{ratio_minus}}',
                '{{ratio_single}}',
                '{{ratio_thumbs}}'
            ),
            array(
                $gallery_css_classes,
                $ratio_plus,
                $ratio_minus,
                $ratio_single,
                $ratio_thumbs
            ),
            $content
        );

        return $content;
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $font_base = $this->themeData->fonts['body']['line-height'];
        $settings = $this->getSettings();
        $padding   = 0;
        $padding  += $settings['nav_spacing'] == 'xs' ? $font_base * 0.25 : 0;
        $padding  += $settings['nav_spacing'] == 'sm' ? $font_base * 0.5  : 0;
        $padding  += $settings['nav_spacing'] == 'md' ? $font_base        : 0;
        $padding  += $settings['nav_spacing'] == 'lg' ? $font_base * 1.5  : 0;

        $config_group = $this->engine->gteOc22() ? TB_Engine::getName() : 'config';

        $w1 = $this->themeData['config']->get($config_group . '_image_thumb_width');
        $h1 = $this->themeData['config']->get($config_group . '_image_thumb_height');
        $w2 = $this->themeData['config']->get($config_group . '_image_additional_width');
        $h2 = $this->themeData['config']->get($config_group . '_image_additional_height');
        $r1 = $w1 / $h1;
        $r2 = $w2 / $h2;
        $n  = $settings['nav_thumbs_num'];

        $css  = '#product_images .mightySlider .frame {';
        $css .= '  height: ' . $h1 . 'px;';
        $css .= '}';
        if ($settings['nav_style'] == 'thumbs' && $settings['nav_position'] != 'bottom') {
            $css .= '#product_images .tb_slides:not(:last-child) {';
            $css .= '  width: calc(' . (((100 * $n) / ($r1 * $n + $r2)) * $r1) . '% + ' . ((($padding * $r2 * $n - $padding * $r2 - $padding * $n) / ($r1 * $n + $r2)) * $r1) . 'px);';
            $css .= '}';
            $css .= '#product_images .tb_thumbs_wrap {';
            $css .= '  width: calc(' . (100 - ((100 * $n) / ($r1 * $n + $r2)) * $r1) . '% - ' . ($padding + (($padding * $r2 * $n - $padding * $r2 - $padding * $n) / ($r1 * $n + $r2)) * $r1) . 'px);';
            $css .= '}';
        }

        $styleBuilder->addCss($css);
    }

    public function getDefaultBoxColors()
    {
        return array(
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
            )
        );
    }

    public function hasTitleStyles()
    {
        return false;
    }
}