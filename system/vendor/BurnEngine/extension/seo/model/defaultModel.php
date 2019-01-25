<?php

class Seo_DefaultModel extends TB_ExtensionModel
{
    /**
     * @param int|null $store_id
     * @return TB_SettingsModel
     */
    public function getSettingsModel($store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->context->getStoreId();
        }

        return $this->engine->getSettingsModel('seo', $store_id);
    }

    public function getSettings($raw = false)
    {
        return array_replace_recursive($this->getDefaultSettings(), (array) $this->getSettingsModel()->getScopeSettings('seo', $raw));
    }

    protected function getDefaultSettings()
    {
        return array(
            'multilingual_keywords'   => 1,
            'language_prefix'         => 1,
            'language_prefix_codes'   => array(),
            'default_language_prefix' => 0,
            'pretty_urls'             => 1,
            'redirect_to_seo'         => 0,
            'hreflang_tag'            => 1,
            'google_microdata'        => 1,
            'facebook_opengraph'      => 1,
            'twitter_card'            => 1
        );
    }
}