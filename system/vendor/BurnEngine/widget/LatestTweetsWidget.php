<?php

class Theme_LatestTweetsWidget extends TB_Widget
{
    protected $areas = array('footer', 'intro', 'content', 'column_left', 'column_right');

    public function onFilter(array &$settings)
    {
        $settings = array_replace($settings, $this->initLangVars(array(
            'is_active'          => 1,
            'title'              => 'Latest Tweets',
            'title_icon'         => '',
            'title_icon_size'    => 100,
            'title_align'        => 'left',
            'follow_button_lang' => 'en'
        ), $settings));

        $settings = array_replace($settings, $this->initFlatVars(array(
            'display_type'       => 'default',
            'style'              => 1,
            'tweets_num'         => 2,
            'hover_actions'      => 0,
            'show_follow_button' => 0,
            'hide_replies'       => 1
        ), $settings));
    }

    public function render(array $view_data = array())
    {
        if ($this->settings['display_type'] == 'default') {
            $view_data['twitter_code'] = $this->themeData['twitter']['code'];
        } else {
            $cache_key = $this->getId() . '.' . (TB_RequestHelper::isRequestHTTPS() ? 'https.': '') . $this->language_code;
            $get_remote_content = true;

            if ($tweets = $this->engine->getCacheVar($cache_key)) {
                $view_data['tweets']  = $tweets;

                $get_remote_content = false;
            }

            if ($get_remote_content) {
                $view_data['tweets'] = $this->getTweets($this->themeData['twitter']);
                if ($this->themeData['system']['cache_enabled'] && isset($this->themeData['system']['cache_widgets']['Theme_LatestTweetsWidget']['ttl'])) {
                    $this->engine->setCacheVar($cache_key, $view_data['tweets'], (int) $this->themeData['system']['cache_widgets']['Theme_LatestTweetsWidget']['ttl'] * 60);
                }
            }

            $view_data['twitter_username'] = $this->themeData['twitter']['username'];
        }

        return parent::render($view_data);
    }

    protected function getTweets($twitter)
    {
        $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";

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
            'oauth_version'          => '1.0',
            'screen_name'            => $twitter['username'],
            'count'                  => (int) $this->settings['tweets_num'],
            'trim_user'              => 0,
            'exclude_replies'        => (int) $this->settings['hide_replies']
        );

        $base_info = $this->buildBaseString($url, 'GET', $oauth);
        $composite_key = rawurlencode($twitter['consumer_secret']) . '&' . rawurlencode($twitter['access_secret']);
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        $options = array(
            CURLOPT_HTTPHEADER     => array($this->buildAuthorizationHeader($oauth), 'Content-Type: application/json', 'Expect:'),
            CURLOPT_HEADER         => false,
            CURLOPT_URL            => $url . '?screen_name=' . $twitter['username'] . '&count=' . (int) $this->settings['tweets_num'] . '&trim_user=0&exclude_replies=' . (int) $this->settings['hide_replies'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );
        $feed = curl_init();
        curl_setopt_array($feed, $options);
        $json = curl_exec($feed);
        curl_close($feed);

        $tweets = array();
        $result = json_decode($json);

        if (isset($result->errors)) {
            foreach ($result->errors as $error) {
                $tweets[] = array('text' => $error->message);
            }

            return array ('error' => $tweets);
        }

        foreach ($result as $key => $tweet) {
            $tweets[$key]['time_ago']    = $this->timeElapsedString(strtotime($tweet->created_at));
            $tweets[$key]['text']        = $this->linkify($tweet->text);
            $tweets[$key]['id_str']      = $tweet->id_str;
            $tweets[$key]['name']        = $tweet->user->name;
            $tweets[$key]['screen_name'] = $tweet->user->screen_name;
            $tweets[$key]['avatar']      = str_replace('_normal.', '_bigger.', (TB_RequestHelper::isRequestHTTPS() ? $tweet->user->profile_image_url_https : $tweet->user->profile_image_url));
        }

        return $tweets;
    }

    protected function linkify($string)
    {
        if (!function_exists('replace_callback')) {
            function replace_callback($matches)
            {
                return "{$matches[1]}{$matches[2]}<a href=\"" . trim($matches[3], '.;"\'?<>[]{}|\\!@#$%^&*()-_') . "\" >{$matches[3]}</a>";
            }
        }

        // linkify URLs
        $string = preg_replace_callback("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", 'replace_callback', $string);
        $string = preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" >$3</a>", $string);
        $string = preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $string);

        // linkify twitter users
        $string = preg_replace(
            '/(^|\s)@(\w+)/',
            '\1<a href="http://twitter.com/\2">@\2</a>',
            $string
        );

        // linkify tags
        $string = preg_replace(
            '/(^|\s)#(\w+)/',
            '\1#<a href="http://search.twitter.com/search?q=%23\2">\2</a>',
            $string
        );

        return $string;
    }

    function timeElapsedString($timestamp){
        //type cast, current time, difference in timestamps
        $timestamp      = (int) $timestamp;
        $current_time   = time();
        $diff           = $current_time - $timestamp;

        //intervals in seconds
        $intervals = array (
            'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute'=> 60
        );

        $trans = $tbLang = $this->engine->getThemeExtension()->loadDefaultTranslation();

        //now we just find the difference
        if ($diff == 0) {
            return $trans['text_just_now'];
        }

        if ($diff < 60) {
            return $diff == 1 ? $trans['text_second_ago'] : sprintf($trans['text_seconds_ago'], $diff);
        }

        if ($diff >= 60 && $diff < $intervals['hour']) {
            $diff = floor($diff/$intervals['minute']);

            return $diff == 1 ? $trans['text_minute_ago'] : sprintf($trans['text_minutes_ago'], $diff);
        }

        if ($diff >= $intervals['hour'] && $diff < $intervals['day']) {
            $diff = floor($diff/$intervals['hour']);

            return $diff == 1 ? $trans['text_hour_ago'] : sprintf($trans['text_hours_ago'], $diff);
        }

        if ($diff >= $intervals['day'] && $diff < $intervals['week']) {
            $diff = floor($diff/$intervals['day']);

            return $diff == 1 ? $trans['text_day_ago'] : sprintf($trans['text_days_ago'], $diff);
        }

        if ($diff >= $intervals['week'] && $diff < $intervals['month']) {
            $diff = floor($diff/$intervals['week']);

            return $diff == 1 ? $trans['text_week_ago'] : sprintf($trans['text_weeks_ago'], $diff);
        }

        if ($diff >= $intervals['month'] && $diff < $intervals['year']) {
            $diff = floor($diff/$intervals['month']);

            return $diff == 1 ? $trans['text_month_ago'] : sprintf($trans['text_months_ago'], $diff);
        }

        if ($diff >= $intervals['year']) {
            $diff = floor($diff/$intervals['year']);

            return $diff == 1 ? $trans['text_year_ago'] : sprintf($trans['text_years_ago'], $diff);
        }

        $trans['text_just_now'];
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
            'product_title' => array(
                'section_name'      => 'Author Title',
                'elements'          => '.tb_tweet h3',
                'type'              => 'google',
                'family'            => 'Montserrat',
                'subsets'           => 'latin',
                'variant'           => 'regular',
                'size'              => 14,
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
            'body' => array(
                '_label' => 'Body',
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
                    'elements'    => 'a:not(:hover)',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 0,
                    'force_print' => 0,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links'
                ),
                'links_hover' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => 'a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 0,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.links_hover'
                )
            ),
            'author' => array(
                '_label' => 'Author',
                'title' => array(
                    'label'       => 'Author title',
                    'elements'    => '.tb_tweet h3 a',
                    'property'    => 'color',
                    'color'       => '#333333',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.text'
                ),
                'text' => array(
                    'label'       => 'Author title (hover)',
                    'elements'    => '.tb_tweet h3 a:hover',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                )
            ),
            'actions' => array(
                '_label' => 'Tweet actions',
                'title' => array(
                    'label'       => 'Links',
                    'elements'    => '.tb_tweet .tb_actions a',
                    'property'    => 'color',
                    'color'       => '#b92616',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent'
                ),
                'text' => array(
                    'label'       => 'Links (hover)',
                    'elements'    => '.tb_tweet .tb_actions a:hover',
                    'property'    => 'color',
                    'color'       => '#000000',
                    'important'   => 1,
                    'force_print' => 1,
                    'can_inherit' => 1,
                    'inherit'     => 1,
                    'inherit_key' => 'column:body.accent_hover'
                )
            )
        );
    }

}