<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class Seo_Catalog_DefaultModel extends Seo_DefaultModel
{
    public function getGeneralSettings($raw = false)
    {
        return (array) $this->getSettingsModel(0)->getScopeSettings('seo_general', $raw);
    }
}