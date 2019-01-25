<?php

$basename = basename(basename(__FILE__), '.php');

require_once DIR_SYSTEM . 'vendor/' . $basename . '/admin/boot.php';
require_once DIR_SYSTEM . 'vendor/' . $basename . '/admin/controller/ModuleController.php';
    
class ControllerExtensionThemeBurnEngine extends Theme_Admin_ModuleController
{
    public function install()
    {
        $this->getEngine()->getThemeExtension()->getInstaller()->installEngine('theme');
    }
}