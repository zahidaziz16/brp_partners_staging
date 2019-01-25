<?php

class Theme_GoogleMapsWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Store Location',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'embed'      => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8358376525007!2d144.955431!3d-37.817313999999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xedc33f230d1355b1!2sEnvato+Pty+Ltd!5e0!3m2!1sbg!2s!4v1402307678320" width="600" height="450" frameborder="0" style="border:0"></iframe>',
            'height'    => 200,
            'fullwidth' => 0
        ), $settings));
    }

    public function render(array $view_data = array())
    {
        $embed = html_entity_decode($this->settings['embed'], ENT_COMPAT, 'UTF-8');
        $height   = $this->settings['height'];

        if (extension_loaded('simplexml') && 0 === stripos($embed, '<iframe')) {
            $embed = str_replace('allowfullscreen', '', $embed);
            $sx = simplexml_load_string($embed);
            $view_data['iframe_attributes'] = array();
            foreach ($sx->attributes() as $key => $value) {
                $view_data['iframe_attributes'][$key] = $value;
            }
            $view_data['iframe_attributes']['height'] = $height;
            $view_data['map_code'] = false;

        } else {
            $view_data['map_code'] = preg_replace('/(height)="[0-9]*"/i', '$1="' . $height . '"',  $embed);
        }

        return parent::render($view_data);
    }

    protected function getBoxClasses()
    {
        $lazyload  = $this->themeData->system['js_lazyload'];
        $classes   = parent::getBoxClasses();
        $classes  .= $this->settings['fullwidth'] ? ' tb_full' : '';
        $classes  .= $lazyload ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $lazyload  = $this->themeData->system['js_lazyload'];
        $data      = parent::getBoxData();
        $data     .= $lazyload ? ' data="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

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