<?php

require_once 'SystemWidget.php';

class Theme_ProductDescriptionSystemWidget extends Theme_SystemWidget
{
    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'block_title_align' => 'default'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'block_title' => false
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

    public function getDefaultBoxColors()
    {
        $parent_colors = parent::getDefaultBoxColors();

        unset($parent_colors['body']['links']);
        unset($parent_colors['body']['links_hover']);

        return $parent_colors;
    }
}