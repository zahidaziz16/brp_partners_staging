<?php

class Theme_TwitterBoxWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'          => 1,
            'title'              => 'Twitter Followers',
            'title_icon'         => '',
            'title_icon_size'    => 100,
            'title_align'        => 'left',
            'follow_button_lang' => 'en',
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'profiles_type'     => 'followers',
            'user_profile'      => 0,
            'profiles_num'      => 12,
            'profiles_rows'     => 2,
            'profile_name'      => 1,
            'profile_border'    => 1,
        ), $settings));
    }

    public function render(array $view_data = array())
    {
        $cache_key = $this->getId() . '.' . $this->language_code;
        $get_remote_content = true;

        if ($twitter_data = $this->engine->getCacheVar($cache_key)) {
            $view_data['twitter_data'] = $twitter_data;
            $get_remote_content = false;
        }

        if ($get_remote_content) {
            $view_data['twitter_data'] = $this->getTwitterData($this->themeData['twitter']);
            if ($this->themeData['system']['cache_enabled'] && isset($this->themeData['system']['cache_widgets']['Theme_TwitterBoxWidget']['ttl'])) {
                $this->engine->setCacheVar($cache_key, $view_data['twitter_data'], (int) $this->themeData['system']['cache_widgets']['Theme_TwitterBoxWidget']['ttl'] * 60);
            }
        }

        $like_box_classes     = '';
        $like_box_classes    .= $this->settings['profile_name'] ? ' tb_show_title' : '';
        $like_box_classes    .= $this->settings['profile_border'] ? ' tb_show_border' : '';

        $view_data['like_box_classes'] = $like_box_classes;
        $view_data['twitter_username'] = $this->themeData['twitter']['username'];

        return parent::render($view_data);
    }

    protected function getTwitterData($twitter)
    {
        $url = "https://api.twitter.com/1.1/users/lookup.json";
        $json = $this->doRequest($url, array('screen_name' => $twitter['username']), $twitter);
        $errors = $this->checkErrors($json);
        if (false !== $errors) {
            return $errors;
        }


        $data = array(
            'name'              => $json[0]->name,
            'screen_name'       => $json[0]->screen_name,
            'followers_count'   => $json[0]->followers_count,
            'profile_image_url' => str_replace('_normal.', '_bigger.', $json[0]->profile_image_url),
            'friends_count'     => $json[0]->friends_count
        );
        
        if ($this->settings['profiles_type'] == 'followers') {
            $url = "https://api.twitter.com/1.1/followers/ids.json";
            $json = $this->doRequest($url, array('screen_name' => $twitter['username']), $twitter);
        } else {
            $url = "https://api.twitter.com/1.1/friends/ids.json";
            $json = $this->doRequest($url, array('screen_name' => $twitter['username']), $twitter);
        }

        $errors = $this->checkErrors($json);
        if (false !== $errors) {
            return $errors;
        }

        $profiles_num = abs((int) $this->settings['profiles_num']);
        if (!$profiles_num) {
            $profiles_num = 1;
        }
        if (!$profiles_num > 100) {
            $profiles_num = 100;
        }

        $ids = array_slice($json->ids, 0, $profiles_num);

        $url = "https://api.twitter.com/1.1/users/lookup.json";
        $json = $this->doRequest($url, array('user_id' => implode(',', $ids), 'include_entities' => 0), $twitter);

        $errors = $this->checkErrors($json);
        if (false !== $errors) {
            return $errors;
        }

        $data['users'] = array();
        foreach ($json as $user) {
            $data['users'][] = array(
                'name'              => $user->name,
                'screen_name'       => $user->screen_name,
                'profile_image_url' => str_replace('_normal.', '_bigger.', $user->profile_image_url)
            );
        }

        return $data;
    }

    protected function doRequest($url, array $url_param, $twitter)
    {
        $default_timezone = date_default_timezone_get();
        date_default_timezone_set('Pacific/Easter');
        $utc_time = strtotime(gmdate("M d Y H:i:s", time()));
        date_default_timezone_set($default_timezone);

        $oauth = array(
            'oauth_consumer_key'     => $twitter['consumer_key'],
            'oauth_nonce'            => $utc_time,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token'            => $twitter['access_token'],
            'oauth_timestamp'        => $utc_time,
            'oauth_version'          => '1.0'
        ) + $url_param;

        $composite_key = rawurlencode($twitter['consumer_secret']) . '&' . rawurlencode($twitter['access_secret']);
        $oauth_signature = base64_encode(hash_hmac('sha1', $this->buildBaseString($url, 'GET', $oauth), $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        $options = array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => array($this->buildAuthorizationHeader($oauth), 'Content-Type: application/json', 'Expect:'),
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => $url . '?' . http_build_query($url_param),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );
        $feed = curl_init();
        curl_setopt_array($feed, $options);
        $json = curl_exec($feed);
        curl_close($feed);

        return json_decode($json);
    }

    protected function checkErrors($json)
    {
        if (isset($json->errors)) {
            $errors = array();
            foreach ($json->errors as $error) {
                $errors[] = $error->message;
            }

            return array ('errors' => $errors);
        } else {
            return false;
        }
    }


    protected function buildBaseString($baseURI, $method, $params) {
        $r = array();
        ksort($params);

        foreach($params as $key => $value){
            $r[] = "$key=" . rawurlencode($value);
        }

        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    protected function buildAuthorizationHeader($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();

        foreach($oauth as $key => $value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);

        return $r;
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
                'letter-spacing'    => '',
                'word-spacing'      => '',
                'transform'         => '',
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
            'twitter_box' => array(
                '_label' => 'Custom widget',
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
                'text_links' => array(
                    'label'       => 'Links',
                    'elements'    => '.tb_text_wrap a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text_links'
                ),
                'text_links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '.tb_text_wrap a:hover',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text_links_hover'
                ),
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
                    'elements'    => '.uiList li a.link:after',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'avatar_title_hover' => array(
                    'label'       => 'Profile title (hover)',
                    'elements'    => '.uiList li:hover a.link:after',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                )
            )
        );
    }
}