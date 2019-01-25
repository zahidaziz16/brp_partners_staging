<?php

class Theme_BannerWidget extends TB_Widget
{
    protected $areas = array('header', 'footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'   => 1,
            'url'         => '',
            'url_target'  => '_self',
            'line_1'      => '',
            'line_2'      => '',
            'line_3'      => '',
            'text_align'  => 'center',
            'text_valign' => 'middle'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'ratio_w'               => 240,
            'ratio_h'               => 150,
            'max_height'            => 150,
            'min_height'            => 0,
            'image'                 => '',
            'image_position'        => 'center',
            'hover_zoom'            => 0,
            'hover_move'            => 0,
            'hover_color'           => 1,
            'zoom_origin'           => 0,
            'zoom_size'             => 1.05,
            'color_opacity'         => 0.5,
            'line_1_padding_top'    => 0,
            'line_1_padding_bottom' => 0,
            'line_1_hover'          => 0,
            'line_1_show_delay'     => 0, // ms
            'line_1_hide_delay'     => 0, // ms
            'line_1_offset'         => 0, // px
            'line_1_move_direction' => 'top', // none, top, right, bottom, left
            'line_2_padding_top'    => 0,
            'line_2_padding_bottom' => 0,
            'line_2_hover'          => 0,
            'line_2_show_delay'     => 0, // ms
            'line_2_hide_delay'     => 0, // ms
            'line_2_offset'         => 0, // px
            'line_2_move_direction' => 'top', // none, top, right, bottom, left
            'line_3_padding_top'    => 0,
            'line_3_padding_bottom' => 0,
            'line_3_hover'          => 0,
            'line_3_show_delay'     => 0, // ms
            'line_3_hide_delay'     => 0, // ms
            'line_3_offset'         => 0, // px
            'line_3_move_direction' => 'top', // none, top, right, bottom, left
        ), $settings));
    }

    public function onEdit(array &$settings)
    {
        if (!empty($settings['image'])  && file_exists(DIR_IMAGE . $settings['image'])) {
            $settings['image_preview'] = $this->getOcModel('tool/image')->resize($settings['image'], 100, 100);
        } else {
            $settings['image_preview'] = $this->getThemeModel()->getNoImage();
        }
    }

    public function render(array $view_data = array())
    {
        $settings = $this->settings;
        $preset   = $this->getPresetData();
        $layout   = $preset ? $preset['styles']['box']['layout'] : $settings['box_styles']['layout'];

        $padding  = '';
        $padding .= $layout['padding_top']    != 0 ? ' tb_pt_' . $layout['padding_top']    : '';
        $padding .= $layout['padding_right']  != 0 ? ' tb_pr_' . $layout['padding_right']  : '';
        $padding .= $layout['padding_bottom'] != 0 ? ' tb_pb_' . $layout['padding_bottom'] : '';
        $padding .= $layout['padding_left']   != 0 ? ' tb_pl_' . $layout['padding_left']   : '';

        $line_1_classes  = '';
        $line_1_classes .= !empty($settings['line_1_padding_top'])    ? ' tb_pt_' . $settings['line_1_padding_top']    : '';
        $line_1_classes .= !empty($settings['line_1_padding_bottom']) ? ' tb_pb_' . $settings['line_1_padding_bottom'] : '';
        $line_1_classes .= !empty($settings['line_1_hover']) ? ' invisible' : '';

        $line_2_classes  = '';
        $line_2_classes .= !empty($settings['line_2_padding_top'])    ? ' tb_pt_' . $settings['line_2_padding_top']    : '';
        $line_2_classes .= !empty($settings['line_2_padding_bottom']) ? ' tb_pb_' . $settings['line_2_padding_bottom'] : '';
        $line_2_classes .= !empty($settings['line_2_hover']) ? ' invisible' : '';

        $line_3_classes  = '';
        $line_3_classes .= !empty($settings['line_3_padding_top'])    ? ' tb_pt_' . $settings['line_3_padding_top']    : '';
        $line_3_classes .= !empty($settings['line_3_padding_bottom']) ? ' tb_pb_' . $settings['line_3_padding_bottom'] : '';
        $line_3_classes .= !empty($settings['line_3_hover']) ? ' invisible' : '';

        if ($settings['max_height']) {
            $w = ($settings['ratio_w'] * $settings['max_height']) / $settings['ratio_h'];
            $h = $settings['max_height'];
        } else {
            $w = $settings['ratio_w'];
            $h = $settings['ratio_h'];
        }

        ob_start();
        imagegif(imagecreate($w, $h));
        $img = ob_get_clean();

        $view_data['ratio_img'] = 'data:image/gif;base64,' . base64_encode($img);
        $view_data['image']     = $settings['image'] ? $this->engine->getContext()->getImageUrl() . $settings['image'] : '';
        $view_data['padding']   = $padding ? $padding : '';
        $view_data['line_1_classes'] = $line_1_classes;
        $view_data['line_2_classes'] = $line_2_classes;
        $view_data['line_3_classes'] = $line_3_classes;

        return parent::render($view_data);
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'line_1' => array(
                'section_name'      => 'Line 1',
                'elements'          => ' .tb_line_1',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 32,
                'line-height'       => 40,
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
            'line_2' => array(
                'section_name'      => 'Line 2',
                'elements'          => ' .tb_line_2',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 24,
                'line-height'       => 30,
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
            'line_3' => array(
                'section_name'      => 'Line 3',
                'elements'          => ' .tb_line_3',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 18,
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

    public function getDefaultBoxColors()
    {
        $default_colors = array(
            'banner' => array(
                '_label' => '',
                'line_1' => array(
                    'label'       => 'Line 1',
                    'elements'    => '.tb_line_1',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'line_2' => array(
                    'label'       => 'Line 2',
                    'elements'    => '.tb_line_2',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'line_3' => array(
                    'label'       => 'Line 3',
                    'elements'    => '.tb_line_3',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'hover_bg' => array(
                    'label'       => 'Hover bg',
                    'elements'    => '.tb_image:before',
                    'property'    => 'background-color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            )
        );

        return $default_colors;
    }

    public function hasTitleStyles()
    {
        return false;
    }
}