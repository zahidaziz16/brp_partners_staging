<?php

class Theme_Catalog_StylePlugin extends TB_ExtensionPlugin
{
    protected $fonts_for_web;

    public function configure(TB_ViewDataBag $themeData)
    {
        $this->eventDispatcher->connect('core:generateExternalCss', array($this, 'onGenerateExternalCss'));;
    }

    public function execute(TB_ViewDataBag $themeData, Request $request)
    {
        if ($themeData->skip_layout) {
            return;
        }

        $this->bootstrap('common');

        $themeData->style  = $this->getSetting('style');
        $themeData->colors = $this->getSetting('colors');

        $themeData->setLazyVar('presets_css', array($this, 'getPresetsCss'));

        $this->buildLayoutClass($themeData);
        $this->buildBodyClass($themeData);
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $style = $this->getThemeData()->style;

        $sections = array(
            'wrapper' => '#wrapper',
            'bottom'  => '#bottom'
        );

        foreach ($sections as $item => $selector) {
            if (!isset($style[$item])) {
                continue;
            }

            if (isset($style[$item]['background']['solid_color_inherit_key'])) {
                $style[$item]['background']['solid_color'] = TB_ColorSchemer::getInstance()->resolveParentColor($this->getThemeData()->colors, $style[$item]['background']['solid_color_inherit_key']);
            }

            $styleBuilder->buildEffectsCss($style[$item], $selector);
        }

        foreach ($this->getSetting('colors') as $group) {
            foreach ($group as $color_rule) {
                $styleBuilder->addGlobalColorRule($color_rule);
            }
        }

        $css_images_url = $this->context->getEngineConfig('css_images_url');
        if (empty($css_images_url) || $css_images_url == 'auto_relative') {
            $css_replace = array('../font/' => '../../../catalog/view/theme/' . $this->context->getBasename() . '/font/');
        } else
        if ($css_images_url == 'auto_full') {
            $css_replace = array('../font/' => '{path}font/');
        } else {
            $css_replace = array('../font/' => str_replace('{{basename}}', $this->context->getBasename(), $css_images_url));
        }

        $this->getThemeData()->registerStylesheetResource('stylesheet/font-awesome.css', null, $css_replace);
        $this->getThemeData()->registerStylesheetResource('stylesheet/jquery_ui/jquery-ui.custom.css');

        $css = '';
        foreach($this->getThemeData()->getStylesheetResources() as $resource) {
            if (!is_file($resource['dir'])) {
                continue;
            }

            $contents = file_get_contents($resource['dir']);

            if (isset($resource['path_replace'])) {
                foreach ($resource['path_replace'] as $search => $replace) {
                    $contents = str_replace($search, $replace, $contents);
                }
            }

            $css .= $contents;
        }

        $styleBuilder->prependExternalCss($css);
    }

    public function getPresetsCss()
    {
        $styleBuilder = new TB_StyleBuilder($this->context, $this->getModel('fonts'));
        $styleBuilder->setBaseLineHeight($this->getThemeData()->fonts['body']['line-height']);
        $this->fonts_for_web = array();

        foreach (array_merge($this->engine->getSettingsModel('preset', 0)->getValues(), $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->getValues()) as $preset) {

            foreach ($preset['styles'] as $style_type => $style_item) {

                $css_selector = '[class].pr_' . $preset['id']; // selector priority hack [class]
                $lang_dir     = $this->getThemeData()->language_direction;

                if (!empty($style_item['colors'])) {
                    $bg_color = isset($style_item['background']['solid_color']) ? $style_item['background']['solid_color'] : null;

                    foreach ($style_item['colors'] as $group_values) {
                        foreach ($group_values as $color_rule) {
                            // The color rule can be string because of colors filtering in self::buildStyles()
                            if (is_array($color_rule)) {
                                $styleBuilder->addScopedColorRule($css_selector, $color_rule, $bg_color);
                            }
                        }
                    }
                }

                if (isset($style_item['font'][$this->language_code])) {
                    $styleBuilder->addFonts($style_item['font'][$this->language_code], $css_selector);

                    foreach ($style_item['font'][$this->language_code] as $key => $font) {
                        if (empty($font['type']) || $font['type'] != 'google') {
                            continue;
                        }

                        $this->fonts_for_web[] = $font;
                    }

                }

                if ($style_type == 'title') {
                    $css_selector .= $css_selector . ' .panel-heading, ' . $css_selector . ' .box-heading';
                }

                $styleBuilder->buildBoxModelCss($style_item['layout'], $css_selector, $lang_dir);
                $styleBuilder->buildBoxModelCss($style_item['layout'], ('.row-wrap' . $css_selector . ' > .row'), $lang_dir, 'padding_y');
                $styleBuilder->buildEffectsCss($style_item, $css_selector);
            }
        }

        return $styleBuilder->getScopedColorsString() . $styleBuilder->getFontsString() . $styleBuilder->getCssString();
    }

    public function onGenerateExternalCss(sfEvent $event)
    {
        if (null === $this->fonts_for_web) {
            $this->getPresetsCss();
        }

        /** @var TB_StyleBuilder $mainStyleBuilder */
        $mainStyleBuilder = $event['styleBuilder'];
        $mainStyleBuilder->addFontsForWeb($this->fonts_for_web);
    }

    protected function buildLayoutClass(TB_ViewDataBag $themeData)
    {
        $style = $themeData->style;

        foreach (array('wrapper', 'bottom') as $item) {
            if (isset($style[$item])) {
                $el_class  = '';
                $el_class .= $style[$item]['layout']['type'] == 'fixed'      ? 'container' : '';
                $el_class .= $style[$item]['layout']['type'] == 'full'       ? 'container-fluid' : '';
                $el_class .= $style[$item]['layout']['type'] == 'full_fixed' ? 'container-fluid tb_content_fixed' : '';
                $el_class .= $style[$item]['layout']['margin_top']     != 0 ? ' tb_mt_' . $style[$item]['layout']['margin_top']     : '';
                $el_class .= $style[$item]['layout']['margin_right']   != 0 ? ' tb_mr_' . $style[$item]['layout']['margin_right']   : '';
                $el_class .= $style[$item]['layout']['margin_bottom']  != 0 ? ' tb_mb_' . $style[$item]['layout']['margin_bottom']  : '';
                $el_class .= $style[$item]['layout']['margin_left']    != 0 ? ' tb_ml_' . $style[$item]['layout']['margin_left']    : '';
                $el_class .= $style[$item]['layout']['padding_top']    != 0 ? ' tb_pt_' . $style[$item]['layout']['padding_top']    : '';
                $el_class .= $style[$item]['layout']['padding_right']  != 0 ? ' tb_pr_' . $style[$item]['layout']['padding_right']  : '';
                $el_class .= $style[$item]['layout']['padding_bottom'] != 0 ? ' tb_pb_' . $style[$item]['layout']['padding_bottom'] : '';
                $el_class .= $style[$item]['layout']['padding_left']   != 0 ? ' tb_pl_' . $style[$item]['layout']['padding_left']   : '';

                $themeData[$item . '_css_classes']  = $el_class;
            }
        }
    }

    protected function buildBodyClass(TB_ViewDataBag $themeData)
    {
        $body_class = 'tb_width_' . $themeData->style['maximum_width'];
        $body_class .= ' tb_lang_' . $themeData->language_direction;
        $body_class .= ' tb_page_' . str_replace('/', '_', $themeData->route);

        if ($themeData->layout_id != $themeData->route_layout_id) {
            $layout_name = TB_Utils::slugify($this->getThemeModel()->getLayoutNameById($themeData->layout_id));
            if (!empty($layout_name)) {
                $body_class .= ' tb_layout_' . str_replace('-', '_', $layout_name);
            }
        }

        $body_class .= $this->engine->getOcCustomer()->isLogged() ? ' is_logged' : '';
        $body_class .= $this->engine->getOcAffiliate()->isLogged() ? ' is_affiliate_logged' : '';

        $themeData->body_class = $body_class . ' ' . str_replace('.', '_', $this->extension->getStylesFileHash());
    }
}