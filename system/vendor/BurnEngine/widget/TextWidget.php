<?php

class Theme_TextWidget extends TB_Widget
{
    protected $areas = array('header', 'footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Text Block',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left',
            'text'             => '<p>Text block body.</p>'
        ), $settings));

        foreach (array_keys($this->engine->getEnabledLanguages()) as $language_code) {
            $settings['lang'][$language_code]['text'] = html_entity_decode($settings['lang'][$language_code]['text'], ENT_COMPAT, 'UTF-8');
        }
    }

    protected function renderContent($content)
    {
        if ($content && TB_RequestHelper::isRequestHTTPS()) {
            $content = str_replace(' src="http://', ' src="https://', $content);
        }

        return parent::renderContent($content);
    }

    public function onRenderWidgetContent($content)
    {
        if ($this->themeData['system']['image_lazyload']) {
            $content = $this->getThemeModel()->alignImagesAttributes($content);
        }

        return $content;
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

}