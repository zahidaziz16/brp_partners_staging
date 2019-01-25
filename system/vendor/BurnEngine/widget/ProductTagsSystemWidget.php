<?php

require_once 'SystemWidget.php';

class Theme_ProductTagsSystemWidget extends Theme_SystemWidget
{
    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'block_title_align' => 'default'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'block_title' => true,
            'inline'      => true
        ), $settings));
    }

    public function onRenderWidgetContent($content)
    {

        $lang_settings = $this->getLangSettings();

        $title_classes  = 'panel-heading';
        $title_classes .= $this->getDistanceClasses('title');
        $title_classes .= $lang_settings['block_title_align'] != 'default' ? ' text-' . $lang_settings['block_title_align'] : '';
        $content = str_replace('panel-heading', $title_classes, $content);

        return $content;
    }

    protected function getBoxClasses()
    {
        $classes = parent::getBoxClasses();
        $inline  = $this->settings['inline'];

        $classes .= $inline ? ' tb_content_inline' : '';

        return $classes;
    }

    public function getDefaultBoxColors()
    {
        return array(
            'tags' => array(
                '_label' => 'Tags',
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => 'a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'bg' => array(
                    'label'       => 'Bg',
                    'elements'    => 'a:not(:hover)',
                    'property'    => 'background-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'bg_hidden_color' => array(
                    'label'       => 'Bg',
                    'elements'    => 'a:not(:hover):before',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tags.bg'
                ),
                'text_hover' => array(
                    'label'       => 'Text (hover)',
                    'elements'    => 'a:hover',
                    'property'    => 'color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'bg_hover' => array(
                    'label'       => 'Bg (hover)',
                    'elements'    => 'a:hover',
                    'property'    => 'background-color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                ),
                'bg_hover_hidden_color' => array(
                    'label'       => 'Bg (hover)',
                    'elements'    => 'a:hover:before',
                    'property'    => 'border-color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'tags.bg_hover'
                ),
            )
        );
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'body' => array(
                'section_name'      => 'Body',
                'elements'          => '',
                'type'              => '',
                'family'            => 'inherit',
                'subsets'           => '',
                'variant'           => '',
                'size'              => 13,
                'line-height'       => 20,
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => false,
                'has_effects'       => false,
                'show_built_styles' => false,
                'multiple_variants' => true,
                'built-in'          => true,
                'can_inherit'       => true
            )
        );
    }
}