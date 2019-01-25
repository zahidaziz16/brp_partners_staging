<?php

interface TB_AdminDataPlugin
{
    public function filterSettings(array &$plugin_settings);
    public function setDataForView(&$plugin_settings, TB_ViewDataBag $themeData);
    public function getConfigKey();
}