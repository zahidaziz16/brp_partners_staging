<?php

require_once dirname(__FILE__) . '/../../model/extensionsModel.php';

class Theme_Admin_ExtensionsModel extends Theme_ExtensionsModel
{
    public function installExtension($name)
    {
        $extensions = $this->engine->getSettingsModel('extensions', 0)->getScopeSettings('extensions');

        if (null == $extensions) {
            $extensions = array();
        }

        $ext = $this->extension;

        if (isset($extensions[$name])) {
            $extension = $this->engine->getExtension($name);
            return array(
              'success' => false,
              'message' => sprintf($ext->translate('extension_already_installed'), $extension->getInitOption('title'))
            );
        }

        $not_installed_extensions = $this->engine->getNotInstalledExtensions();
        if (!isset($not_installed_extensions[$name])) {
            return array(
              'success' => false,
              'message' => sprintf($ext->translate('extension_cannot_be_found'), $name)
            );
        }
        $extension = $not_installed_extensions[$name];

        if (method_exists($extension, 'checkRequirements')) {
            $result = call_user_func(array($extension, 'checkRequirements'));
            if (true !== $result) {
                return array(
                  'success' => false,
                  'message' => (string) $result
                );
            }
        }

        if (method_exists($extension, 'install')) {
            call_user_func(array($extension, 'install'));
        }

        $extensions[$name] = array(
            'isntalled_on' => date('d-m-Y h:i')
        );

        $this->engine->getSettingsModel('extensions', 0)->setAndPersistScopeSettings('extensions', $extensions);

        return array(
          'success' => true,
          'message' => sprintf($ext->translate('extension_has_been_installed'), $extension->getInitOption('title'))
        );
    }

    public function uninstallExtension($name)
    {
        $extensions = $this->engine->getSettingsModel('extensions', 0)->getScopeSettings('extensions');

        if (null == $extensions) {
            $extensions = array();
        }

        $ext = $this->extension;

        if (!isset($extensions[$name])) {
            return array(
              'success' => false,
              'message' => sprintf($ext->translate('extension_cannot_be_found'), $name)
            );
        }

        $extension = $this->engine->getExtension($name);
        if (method_exists($extension, 'uninstall')) {
            call_user_func(array($extension, 'uninstall'));
        }
        $result = array(
          'success' => true,
          'message' => sprintf($ext->translate('extension_has_been_uninstalled'), $extension->getInitOption('title'))
        );

        unset($extensions[$name]);

        $this->engine->getSettingsModel('extensions', 0)->setAndPersistScopeSettings('extensions', $extensions);

        return $result;
    }

    public function requireVQMod()
    {
        if ($this->engine->gteOc2()) {
            return true;
        }

        if (!class_exists('VQMod')) {
            return 'vQmod has not been found. This extension needs vQmod in order to function properly. Please, refer to <a href="https://github.com/vqmod/vqmod/wiki/Installing-vQmod-on-OpenCart">vQmod documentation</a>.';
        }

        if (!defined('TB_CORE_MOD')) {
            $info = $this->engine->getThemeExtension()->getInstaller()->installVQmod('tb_core');
            if (true !== $info) {
                return $info;
            }
        }

        return true;
    }
}