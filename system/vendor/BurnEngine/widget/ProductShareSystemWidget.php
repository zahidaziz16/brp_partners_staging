<?php

require_once 'SystemWidget.php';

class Theme_ProductShareSystemWidget extends Theme_SystemWidget
{

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'block_title_align' => 'default'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'block_title'         => true,
            'inline'              => true,
            'button_facebook'     => true,
            'button_facebook_share' => false,
            'button_twitter'      => true,
            'button_google'       => true,
            'button_pinterest'    => 0,
            'button_stumbleupon'  => 0,
            'button_linkedin'     => 0,
            'button_custom'       => '',
        ), $settings));
    }

    public function onRenderWidgetContent($content)
    {
        $lang_settings = $this->getLangSettings();

        $title_classes  = 'panel-heading ';
        $title_classes .= $this->getDistanceClasses('title');
        $title_classes .= $lang_settings['block_title_align'] != 'default' ? ' text-' . $lang_settings['block_title_align'] : '';
        $content = str_replace('panel-heading', $title_classes, $content);

        return $content;
    }

    protected function getBoxClasses()
    {
        $settings = $this->settings;
        $lazyload = $this->themeData->system['js_lazyload'];
        $classes  = parent::getBoxClasses();
        $classes .= $settings['inline'] ? ' tb_content_inline' : '';
        $classes .= $lazyload ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $lazyload = $this->themeData->system['js_lazyload'];
        $data     = parent::getBoxData();
        $data    .= $lazyload ? ' data="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

        return $data;
    }

    public function getDefaultBoxFonts()
    {
        return array();
    }

    public function getDefaultBoxColors()
    {
        return array();
    }
}