<?php

class Theme_SeparatorWidget extends TB_Widget
{
    protected $areas = array('header', 'footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'         => 1,
            'title'             => '',
            'title_align'       => 'left'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'type'              => 'border',
            'border_size'       => 1,
            'border_style'      => 'solid'
        ), $settings));
    }

    public function render(array $view_data = array())
    {
        $b_height  = $this->settings['border_size'];

        $separator_classes    = 'border';
        if ($this->settings['border_style'] != 'solid') {
          $separator_classes .= ' border-' . $this->settings['border_style'];
        }
        $separator_styles     = '';
        $separator_styles    .= $this->getLangTitle() ? 'margin-top: -' . ceil($b_height / 2) . 'px;' : '';
        $separator_styles    .= 'border-bottom-width: ' . $this->settings['border_size'] . 'px;';
        
        $view_data['separator_classes'] = $separator_classes;
        $view_data['separator_styles']  = $separator_styles;

        return parent::render($view_data);
    }

    protected function getBoxClasses()
    {
        $lang_vars = $this->getLangSettings();

        $classes = parent::getBoxClasses();
        $classes .= ' text-' . $lang_vars['title_align'];

        return $classes;
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'separator_title' => array(
                'section_name'      => 'Title',
                'elements'          => '.tb_title',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => 'latin',
                'variant'           => 'regular',
                'size'              => 11,
                'line-height'       => 20,
                'letter-spacing'    => 1,
                'word-spacing'      => '',
                'transform'         => 'uppercase',
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
        return array(
            'separator' => array(
                '_label' => '',
                'title' => array(
                    'label'       => 'Title',
                    'elements'    => '
                        .tb_title
                    ',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.titles'
                ),
                'title_bg' => array(
                    'label'       => 'Title Background',
                    'elements'    => '
                        .tb_title
                    ',
                    'property'    => 'background-color',
                    'color'       => '',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 0,
                    'inherit_key' => 'column:body.accent'
                ),
                'border_color' => array(
                    'label'       => 'Border',
                    'elements'    => '
                        .border
                    ',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'row:body.column_border'
                )
            )
        );
    }

    public function getPresentationTitle()
    {
        $title = $this->getName();
        $lang_title = $this->getLangTitle();
        if (!empty($lang_title)) {
            $title = $lang_title . ' <span>(' . $title . ')</span>';
        }

        return $title;
    }

    public function hasTitleStyles()
    {
        return false;
    }
}