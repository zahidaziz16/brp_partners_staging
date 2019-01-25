<?php

class Theme_FacebookLikeboxWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'        => 1,
            'title'            => 'Facebook Page',
            'title_icon'       => '',
            'title_icon_size'  => 100,
            'title_align'      => 'left',
            'page_url'         => 'http://facebook.com/themeburn'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'like_box_style'    => 'custom',
            'like_button_style' => 'button',
            // 'user_profile'      => 0,
            'profiles_num'         => 12,
            'profiles_rows'        => 2,
            'profile_name'         => 1,
            'profile_border'       => 1,
            'default_small_header' => false,
            'default_hide_cover'   => false,
        ), $settings));
    }

    public function render(array $view_data = array())
    {   
        if ($this->settings['like_box_style'] == 'custom') {

            $cache_key = $this->getId() . '.' . $this->language_code . '.' . $this->themeData->facebook['locale'] . '.' . $this->engine->getContext()->getStoreId();
            $get_remote_content = true;

            if ($cached = $this->engine->getCacheVar($cache_key)) {
                $view_data['content'] = $cached['content'];
                $view_data['likes']   = $cached['likes'];

                $get_remote_content = false;
            }

            if ($get_remote_content) {
                $url = 'https://www.facebook.com/v2.3/plugins/page.php?href=' . $this->settings['lang'][$this->language_code]['page_url'] . '&locale=es_ES&width=1100&height=400';
                $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                curl_setopt($ch, CURLOPT_URL, $url);

                $result = curl_exec($ch);
                $doc = phpQuery::newDocument($result);

                if ($this->themeData->system['image_lazyload']) {
                    $content = $doc
                        // ->find('head, meta, style, script, .pluginBoxDivider, .pvs.phm, ._8u._42ef > table > tbody > tr + tr')->remove()->end()
                        // ->find('.fsl > a')->addClass('tb_main_color')->end()
                        // ->find('head, link, title, meta, style, script, .pluginBoxDivider, .pvs.phm, .clearfix.pam')->remove()->end()
                        // ->find("body")->html();
                        ->find("img")->addClass("lazyload")->end()
                        ->find(".pluginFacepile")->html();
                } else {
                    $content = $doc
                        ->find(".pluginFacepile")->html();
                }

                $num_likes = number_format(abs(intval(filter_var($doc->find("._1drq")->html(), FILTER_SANITIZE_NUMBER_INT))));
                if (!$num_likes) {
                    $num_likes = number_format(abs(intval(filter_var($doc->find("._3-8w")->html(), FILTER_SANITIZE_NUMBER_INT))));
                }
                $page_title = $doc->find("._1drp")->html();
                $text_people_like = sprintf($this->engine->getThemeExtension()->translate('text_people_like'), $num_likes, $this->settings['lang'][$this->language_code]['page_url'], $page_title);

                $content = '<div class="plm">' . $text_people_like . '</div>' . $content;
                $content = str_replace('<img', '<img width="50" height="50"', $content);
                if ($this->themeData->system['image_lazyload']) {
                    $content = str_replace('src', 'src="' . preg_replace('(^https?://)', '//', $this->themeData->theme_catalog_image_url) . 'pixel.gif" data-aspectratio="1/1" data-src', $content);
                }

                $view_data['content']  = $content;

                if ($this->themeData['system']['cache_enabled'] && isset($this->themeData['system']['cache_widgets']['Theme_FacebookLikeboxWidget']['ttl'])) {
                    $this->engine->setCacheVar($cache_key, array(
                        'content' => $view_data['content'],
                        'likes'   => $num_likes
                    ), (int) $this->themeData['system']['cache_widgets']['Theme_FacebookLikeboxWidget']['ttl'] * 60);
                }
            }

        }

        $like_box_classes = '';
        $like_box_classes .= $this->settings['profile_name'] ? ' tb_show_title' : '';
        $like_box_classes .= $this->settings['profile_border'] ? ' tb_show_border' : '';

        $view_data['like_box_classes'] = $like_box_classes;

        return parent::render($view_data);
    }

    public function buildStyles(TB_StyleBuilder $styleBuilder)
    {
        $font_title = $this->settings['title_styles']['font'][$this->language_code]['title'];
        $font_base  = $this->themeData->fonts['body']['line-height'];

        if (!($font_title['inherit_mask'] & 1)) {
            $css = '
            #' . $this->getDomId() . ' .tb_social_box .tb_social_button {
              margin-top: ' . (($this->themeData->calculateLineHeight($font_title['size'], $font_base) - 20) * 0.5) . 'px;
            }
            ';
            $styleBuilder->addCss($css);
        }
    }

    protected function getBoxClasses()
    {
        $classes   = parent::getBoxClasses();
        $lazyload  = $this->themeData->system['js_lazyload'];

        $classes  .= !$this->getLangTitle() ? ' no_title' : '';
        $classes  .= $lazyload ? ' lazyload' : '';

        return $classes;
    }

    protected function getBoxData()
    {
        $data      = parent::getBoxData();
        $lazyload  = $this->themeData->system['js_lazyload'];

        $data .= $lazyload ? ' data="' . $this->themeData->system['js_lazyload_expand'] . '"' : '';

        return $data;
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
                'letter-spacing'    => 0,
                'word-spacing'      => 0,
                'transform'         => 'none',
                'has_size'          => true,
                'has_line_height'   => true,
                'has_spacing'       => false,
                'has_effects'       => false,
                'show_built_styles' => false,
                'multiple_variants' => true,
                'built-in'          => true,
                'can_inherit'       => true,
                'css_weight'        => '',
                'css_style'         => ''
            ),
            'title' => array(
                'section_name'      => 'Block Title',
                'elements'          => ' h2, .tb_social_button',
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
        return array(
            'facebook_body' => array(
                '_label' => 'Body',
                'title' => array(
                    'label'       => 'Block title',
                    'elements'    => 'h2',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.titles'
                ),
                'text' => array(
                    'label'       => 'Text',
                    'elements'    => '',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'links' => array(
                    'label'       => 'Links',
                    'elements'    => 'a',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => 'a:hover',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                )
            ),
            'facebook_connections' => array(
                '_label' => 'Custom widget',
                'avatar_bg' => array(
                    'label'       => 'Profile image border',
                    'elements'    => '.uiList li img',
                    'property'    => 'background-color',
                    'color'       => '#ffffff',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                ),
                'avatar_title' => array(
                    'label'       => 'Profile title',
                    'elements'    => '.uiList li .link:after',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'avatar_title_hover' => array(
                    'label'       => 'Profile title (hover)',
                    'elements'    => '.uiList li:hover a.link:after',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            ),
            'facebook_default' => array(
                '_label' => 'Default Widget',
                'widget_border' => array(
                    'label'       => 'Widget border',
                    'elements'    => '.tb_fb_likebox.tb_default',
                    'property'    => 'border-color',
                    'color'       => '#dddddd',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 0,
                    'inherit'     => 0,
                    'inherit_key' => ''
                )
            )
        );
    }
}