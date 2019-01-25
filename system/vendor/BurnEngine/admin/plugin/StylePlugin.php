<?php

class Theme_Admin_StylePlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    protected $sections = array('wrapper', 'bottom');

    public function getConfigKey()
    {
        return 'style';
    }

    public function filterAfter()
    {
        return 'colors';
    }

    public function filterSettings(array &$style_settings)
    {
        $default_settings = array(
            // Site width
            'maximum_width' => 1000,

            // Responsive
            'responsive'            => true,
            'mobile_menu_padding'   => '20px',

            // Sticky header
            'sticky_header'         => 1,
            'sticky_header_style'   => 'default',
            'sticky_header_layout'  => 'full_fixed',
            'sticky_header_padding' => '10px 0',

            // System messages
            'msg_position'   => 'topRight',
            'msg_stack'      => 1,
            'msg_timeout'    => 5000,

            'custom_loading' => '',
            'wrapper'        => array(),
            'bottom'         => array()
        );

        $style_settings = TB_FormHelper::initFlatVarsSimple($default_settings, $style_settings);

        $this->initStyleSections($style_settings);
    }

    public function saveData($post_data)
    {
        /** @var Theme_Admin_LayoutBuilderModel $layoutBuilderModel */
        $layoutBuilderModel = $this->getModel('layoutBuilder');

        foreach ($this->sections as $section) {
            if (isset($post_data[$this->getConfigKey()][$section])) {
                $layoutBuilderModel->cleanSettingsDataBeforePersist($post_data[$this->getConfigKey()][$section]);
            }
        }

        return array(
            $this->getConfigKey() => $post_data[$this->getConfigKey()]
        );
    }

    public function setDataForView(&$style_settings, TB_ViewDataBag $themeData)
    {

    }

    protected function initStyleSections(&$style_settings)
    {
        $section_default_vars = array(
            'layout' => array(
                'type'               => 'full',
                'margin_top'         => 0,
                'margin_right'       => 0,
                'margin_bottom'      => 0,
                'margin_left'        => 0,
                'margin_rtl_mode'    => 1,
                'padding_top'        => 0,
                'padding_right'      => 0,
                'padding_bottom'     => 0,
                'padding_left'       => 0,
                'padding_rtl_mode'   => 1
            ),
            'box_shadow' => array(
                'rows' => array()
            ),
            'background' => array(
                'solid_color'         => '',
                'solid_color_opacity' => 100,
                'rows' => array()
            ),
            'border' => array(
                'top'      => array(),
                'right'    => array(),
                'bottom'   => array(),
                'left'     => array(),
                'rtl_mode' => 1
            ),
            'border_radius' => array(
                'top_left'     => 0,
                'top_right'    => 0,
                'bottom_right' => 0,
                'bottom_left'  => 0,
                'rtl_mode'     => 1
            )
        );

        foreach ($this->sections as $section) {

            $section_vars = $section_default_vars;

            // Defaults Override
            if ($section == 'bottom') {
                $section_vars['layout']['padding_top']     = 30;
                $section_vars['layout']['padding_bottom']  = 30;
                $section_vars['background']['solid_color'] = '#ffffff';
            }

            foreach ($section_vars as $key => $value) {
                if (!isset($style_settings[$section][$key])) {
                    $style_settings[$section][$key] = array();
                }

                if ($key == 'background' && !empty($style_settings[$section][$key]['solid_color_inherit_key'])) {
                    $value['solid_color_inherit_key'] = '';
                }

                $style_settings[$section][$key] = TB_FormHelper::initFlatVarsSimple($value, $style_settings[$section][$key]);

                if (isset($style_settings[$section][$key]['solid_color_inherit_key'])) {
                    $style_settings[$section][$key]['solid_color'] = TB_ColorSchemer::getInstance()->resolveParentColor($this->getThemeData()->colors, $style_settings[$section][$key]['solid_color_inherit_key']);
                }
            }

            $this->initSectionBoxShadow($style_settings[$section]);
            $this->initSectionBackground($style_settings[$section]);
            $this->initSectionBorder($style_settings[$section]);
        }
    }


    protected function initSectionBoxShadow(&$section)
    {
        foreach ($section['box_shadow']['rows'] as &$row) {
            $box_shadows_vars = array(
                'size_x'   => 0,
                'size_y'   => 0,
                'angle'    => 0,
                'distance' => 0,
                'inner'    => 0,
                'blur'     => 0,
                'spread'   => 0,
                'color'    => '#000000',
                'opacity'  => 1
            );
            $row = TB_FormHelper::initFlatVarsSimple($box_shadows_vars, $row);
        }
    }

    protected function initSectionBackground(&$section)
    {
        $gradient_vars = array(
            'background_type'     => 'gradient',
            'type'                => 'linear',
            'angle'               => 0,
            'container'           => 'row',
            'position'            => 'center',
            'position_x_metric'   => 'px',
            'position_y_metric'   => 'px',
            'position_x'          => 0,
            'position_y'          => 0,
            'repeat'              => 'no-repeat',
            'size'                => 'auto',
            'size_x_metric'       => 'px',
            'size_y_metric'       => 'px',
            'size_x'              => 1,
            'size_y'              => 1,
            'attachment'          => 'scroll',
            'colors'              => array()
        );

        $color_vars = array(
            'color'       => '#000000',
            'offset_auto' => 1,
            'offset'      => 100,
            'opacity'     => 100
        );

        $image_vars = array(
            'background_type'     => 'image',
            'image'               => '',
            'container'           => 'row',
            'position'            => 'center',
            'position_x_metric'   => 'px',
            'position_y_metric'   => 'px',
            'position_x'          => 0,
            'position_y'          => 0,
            'repeat'              => 'no-repeat',
            'size'                => 'auto',
            'size_x_metric'       => 'px',
            'size_y_metric'       => 'px',
            'size_x'              => 1,
            'size_y'              => 1,
            'attachment'          => 'scroll'
        );

        foreach ($section['background']['rows'] as $key => &$bg_row) {
            switch ($bg_row['background_type']) {
                case 'gradient':
                    $bg_row = TB_FormHelper::initFlatVarsSimple($gradient_vars, $bg_row);
                    foreach ($bg_row['colors'] as &$color_row) {
                        $color_row = TB_FormHelper::initFlatVarsSimple($color_vars, $color_row);
                    }
                    break;
                case 'image':
                    $bg_row = TB_FormHelper::initFlatVarsSimple($image_vars, $bg_row);
                    if ($bg_row['image'] && file_exists(DIR_IMAGE . $bg_row['image'])) {
                        $bg_row['preview'] = $this->getOcModel('tool/image')->resize($bg_row['image'], 100, 100);
                    } else {
                        unset($section['background']['rows'][$key]);
                    }
                    break;
            }
        }
    }

    protected function initSectionBorder(&$section)
    {
        $border_vars = array(
            'width'   => 0,
            'style'   => 'solid',
            'color'   => '#000000',
            'opacity' => 100
        );

        foreach (array('top', 'right', 'bottom', 'left') as $side) {
            $section['border'][$side] = TB_FormHelper::initFlatVarsSimple($border_vars, $section['border'][$side]);
        }
    }
}