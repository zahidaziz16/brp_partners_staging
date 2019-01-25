<?php

require_once 'AbstractHeaderSystemWidget.php';

class Theme_HeaderLogoSystemWidget extends AbstractHeaderSystemWidget
{
    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initFlatVars(array(
            'max_width_lg'      => 0,
            'max_height_lg'     => 0,
            'max_width_md'      => 0,
            'max_height_md'     => 0,
            'max_width_sm'      => 0,
            'max_height_sm'     => 0,
            'max_width_xs'      => 0,
            'max_height_xs'     => 0,
            'max_width_sticky'  => 0,
            'max_height_sticky' => 0,
            'text_logo'         => 0,
            'text_logo_new_row' => 380,
            'logo_width'        => 0,
            'logo_height'       => 0
        ), $settings));

        $logo = DIR_IMAGE . $this->engine->getOcConfig('config_logo');
        if (is_file($logo)) {
            list($settings['logo_width'], $settings['logo_height']) = getimagesize($logo);
        }
    }

    public function onRenderWidgetContent($content)
    {
        $content .= '<span class="tbToggleButtons tbMobileVisible tbMobileHidden">';
        $content .= '  <span class="tb_toggle btn btn-lg tb_no_text fa fa-bars tbToggleHeader tbMobileVisible tbMobileHidden"></span>';
        if ($this->themeData['common']['checkout_enabled']) {
            $content .= '  <span class="tb_toggle btn btn-lg tb_no_text ' . (!empty($this->themeData['system.cart_menu']['cart_icon']) ? $this->themeData['system.cart_menu']['cart_icon'] : 'fa-shopping-cart') . ' tbToggleCart tbMobileVisible tbMobileHidden"></span>';
        }
        // $content .= '  <span class="tb_toggle btn btn-lg fa-search tbToggleSearch tbMobileVisible tbMobileHidden"></span>';
        $content .= '</span>';

        return $content;
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $critical_width  = 0;
        $wrapper_layout  = $this->themeData->style['wrapper']['layout'];
        $header_layout   = $this->themeData->header_layout_settings;
        $base            = $this->themeData->fonts['body']['line-height'];
        $logo            = $this->settings;

        if (!empty($this->settings['logo_height'])) {
            $logo['ratio'] = $logo['logo_width'] / $logo['logo_height'];

            if (!empty($logo['max_width_xs']) || !empty($logo['max_height_xs'])) {
                $critical_width += !empty($logo['max_width_xs'])  ? $logo['max_width_xs'] + 10 * $base : 0;
                $critical_width += !empty($logo['max_height_xs']) ? $logo['max_height_xs'] * $logo['ratio'] + 10 * $base : 0;
            }
            if (!empty($logo['max_width_sm']) || !empty($logo['max_height_sm']) && $critical_width == 0) {
                $critical_width += !empty($logo['max_width_sm'])  ? $logo['max_width_sm'] + 10 * $base : 0;
                $critical_width += !empty($logo['max_height_sm']) ? $logo['max_height_sm'] * $logo['ratio'] + 10 * $base : 0;
            }
            if (!empty($logo['max_width_md']) || !empty($logo['max_height_md']) && $critical_width == 0) {
                $critical_width += !empty($logo['max_width_md'])  ? $logo['max_width_md'] + 10 * $base : 0;
                $critical_width += !empty($logo['max_height_md']) ? $logo['max_height_md'] * $logo['ratio'] + 10 * $base : 0;
            }
            if (!empty($logo['max_width_md']) || !empty($logo['max_height_md']) && $critical_width == 0) {
                $critical_width += !empty($logo['max_width_md'])  ? $logo['max_width_md'] + 10 * $base : 0;
                $critical_width += !empty($logo['max_height_md']) ? $logo['max_height_md'] * $logo['ratio'] + 10 * $base : 0;
            }
            if (!empty($logo['max_width_lg']) || !empty($logo['max_height_lg']) && $critical_width == 0) {
                $critical_width += !empty($logo['max_width_lg'])  ? $logo['max_width_lg'] + 10 * $base : 0;
                $critical_width += !empty($logo['max_height_lg']) ? $logo['max_height_lg'] * $logo['ratio'] + 10 * $base : 0;
            }
            if ($critical_width == 0) {
                $critical_width += $logo['logo_width'] + 10 * $base;
            }
            $critical_width += $wrapper_layout['margin_left'];
            $critical_width += $wrapper_layout['margin_right'];
            $critical_width += $wrapper_layout['padding_left'];
            $critical_width += $wrapper_layout['padding_right'];
            $critical_width += $header_layout['margin_left'];
            $critical_width += $header_layout['margin_right'];
            $critical_width += $header_layout['padding_left'];
            $critical_width += $header_layout['padding_right'];
        } else {
            $critical_width += !empty($logo['text_logo_new_row']) ? $logo['text_logo_new_row'] : 380;
        }

        $css = '
            @media (max-width: ' . $critical_width . 'px) {
              #wrapper #header .tbLogoCol {
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -moz-box-orient: vertical;
                -moz-box-direction: normal;
                -ms-flex-direction: column;
                -webkit-flex-direction: column;
                flex-direction: column;
              }
              #wrapper #header .tbLogoCol > * {
                -webkit-box-flex: 0;
                -moz-box-flex: 0;
                -ms-flex: 0 1 auto;
                -webkit-flex: 0 1 auto;
                flex: 0 1 auto;
              }
              #wrapper #header .tbLogoCol > * + * {
                margin-top: ' . ($base * 0.75) . 'px;
              }
            }
        ';
        $styleBuilder->addCss($css);
    }

    public function getDefaultBoxColors()
    {
        return array(
            'body' => array(
                '_label' => '',
                'text_logo' => array(
                    'label'       => 'Text logo',
                    'elements'    => 'a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'text_logo_hover' => array(
                    'label'       => 'Text logo (hover)',
                    'elements'    => 'a:hover',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                )
            )
        );
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'body' => array(
                'section_name'      => 'Text logo',
                'elements'          => '',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 13,
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
            )
        );
    }

    public function hasTitleStyles()
    {
        return false;
    }
}