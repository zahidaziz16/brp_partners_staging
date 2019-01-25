<?php

class Theme_Admin_TwitterPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    public function getConfigKey()
    {
        return 'twitter';
    }

    public function filterSettings(array &$twitter_settings)
    {
        $vars = array(
            'code'            => '',
            'username'        => '',
            'consumer_key'    => '',
            'consumer_secret' => '',
            'access_token'    => '',
            'access_secret'   => ''
        );

        $twitter_settings = TB_FormHelper::initLangVarsSimple($vars, $twitter_settings, $this->engine->getEnabledLanguages());
    }

    public function setDataForView(&$twitter_settings, TB_ViewDataBag $themeData)
    {

    }

    public function saveData($post_data)
    {
        foreach ($post_data[$this->getConfigKey()] as &$vars) {
            $vars['code'] = base64_decode(str_rot13($vars['code']));
        }

        return array(
            $this->getConfigKey() => $post_data[$this->getConfigKey()]
        );
    }
}