<?php

require_once 'SystemWidget.php';

class Theme_PageTitleSystemWidget extends Theme_SystemWidget
{

    public function getDefaultBoxColors()
    {
        return array(
            'body' => array(
                '_label' => '',
                'title' => array(
                    'label'       => 'Title',
                    'elements'    => 'h1',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                )
            )
        );
    }

    public function getDefaultBoxFonts()
    {
        return array(
            'h1' => array(
                'section_name'      => 'Title',
                'elements'          => 'h1',
            ),
        );
    }

    public function hasTitleStyles()
    {
        return false;
    }
}