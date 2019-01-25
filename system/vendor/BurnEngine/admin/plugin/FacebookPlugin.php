<?php

class Theme_Admin_FacebookPlugin extends TB_ExtensionPlugin implements TB_AdminDataPlugin
{
    public function getConfigKey()
    {
        return 'facebook';
    }

    public function filterSettings(array &$facebook_settings)
    {
        $vars = array(
            'sdk_enabled' => 1,
            'app_id'      => '',
            'locale'      => 'en_US'
        );

        $facebook_settings = TB_FormHelper::initLangVarsSimple($vars, $facebook_settings, $this->engine->getEnabledLanguages());
    }

    public function setDataForView(&$twitter_settings, TB_ViewDataBag $themeData)
    {

    }

    public function saveData($post_data)
    {
        return array(
            $this->getConfigKey() => $post_data[$this->getConfigKey()]
        );
    }
}