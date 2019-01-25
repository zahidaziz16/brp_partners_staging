<?php

class TB_AdminController extends TB_Controller
{
    /**
     * @var Theme_Admin_Extension
     */
    protected $extension;

    /**
     * @var TB_AdminUrl
     */
    protected $tbUrl;

    /**
     * @var array
     */
    protected $error;

    /**
     * @var array
     */
    protected $section_map = array();

    public function __construct(TB_Engine $engine, TB_AdminExtension $extension)
    {
        parent::__construct($engine, $extension);

        $this->tbUrl = $extension->getTbUrl();
        $this->error = array();

        static $booted = false;

        if (!$booted) {
            $theme_settings = $this->themeData['theme_settings'];

            $this->engine->getThemeExtension()->setPluginsViewData($theme_settings);

            $this->themeData['theme_settings'] = $theme_settings;
            $this->getThemeModel()->setSettings($theme_settings);

            $booted = true;
        }


        $this->initDataVars();
        $this->engine->getWidgetManager()->loadWidgetClasses();
    }

    protected function getLivePreviewUrl()
    {
        if ($key = $this->engine->getOcConfig()->get($this->engine->getThemeId() . '_livePreviewToken')) {
            $parts = explode('!*!', $key);
            if (count($parts) != 3) {
                throw new Exception("Invalid live preview token: " . var_dump($parts));
            }
            if ($parts[1] == TB_Utils::getIp() && $parts[2] + 14400 > time()) {
                return htmlspecialchars_decode($parts[0]);
            } else {
                $this->engine->getDbSettingsHelper('setting')->deleteKey($this->engine->getThemeId() . '_livePreviewToken', $this->context->getStoreId());
            }
        }

        return false;
    }

    public function hasAjax($section)
    {
        return $this->section_map[$section]['ajax'];
    }

    public function getSectionAttributes($section, $id)
    {
        preg_match('/^.*_(.*)Controller$/', get_class($this), $matches);
        $href = $this->tbUrl->generate($matches[1] . '/renderSection', 'section=' . $section);

        if ($this->hasAjax($section) && $this->engine->getConfig('admin_ajax')) {
            $result = 'href="' . $href . '"';
        } else {
            $result = 'href="' . '#' . trim($id, '#') . '" data-url="' . $href . '"';;
        }

        return $result;
    }

    public function renderSection($section = null, $data = array())
    {
        if (null !== $section && !isset($this->section_map[$section])) {
            return;
        }

        $is_ajax = false;
        $this->data = array_merge($this->data, $data);

        if  (null == $section && $this->engine->getConfig('admin_ajax')) {
            if (!isset($this->request->get['section'])) {
                return;
            }

            $section = (string) $this->request->get['section'];

            if (!isset($this->section_map[$section])) {
                return;
            }

            $is_ajax = true;
        } else
        if (!isset($this->section_map[$section]) || ($this->section_map[$section]['ajax']) && $this->engine->getConfig('admin_ajax')) {
            return;
        }


        $hook_method = 'render' . TB_Utils::camelize($section) . 'Section';
        if (method_exists($this, $hook_method)) {
            $this->$hook_method();
        }

        $this->data['section'] = $section;

        if (isset($this->section_map[$section]['group'])) {
            $hook_method = 'render' . TB_Utils::camelize($this->section_map[$section]['group']) . 'Group';
            if (method_exists($this, $hook_method)) {
                $this->data['group'] = $this->section_map[$section]['group'];

                $this->$hook_method($section);
            }
        }

        if ($is_ajax) {
            $this->renderTemplate($this->section_map[$section]['template']);
        } else {
            echo $this->fetchTemplate($this->section_map[$section]['template'], $this->data);
        }
    }

    protected function initDataVars()
    {
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['error_alert'] = '';
        if (isset($this->session->data['error_alert'])) {
            $this->data['error_alert'] = $this->session->data['error_alert'];
            unset($this->session->data['error_alert']);
        };

        $this->data['warning_alert'] = '';
        if (isset($this->session->data['warning_alert'])) {
            $this->data['warning_alert'] = $this->session->data['warning_alert'];
            unset($this->session->data['warning_alert']);
        };

        $this->data['success_alert'] = '';
        if (isset($this->session->data['success_alert'])) {
            $this->data['success_alert'] = $this->session->data['success_alert'];
            unset($this->session->data['success_alert']);
        };

        $this->data['theme_settings']  = $this->engine->getThemeExtension()->getThemeModel()->getSettings();
        $this->data['languages']       = $this->engine->getEnabledLanguages();
        $this->data['stores']          = $this->getOcModel('setting/store')->getStores();
        $this->data['all_stores']      = $this->engine->getThemeModel()->getAllStores();
        $this->data['no_image']        = $this->getOcModel('tool/image')->resize('no_image.jpg', 100, 100);
        $this->data['tbUrl']           = $this->tbUrl;
        $this->data['token']           = $this->session->data['token'];
        $this->data['show_extensions'] = count($this->engine->getExtensions()) + count($this->engine->getNotInstalledExtensions()) - count($this->engine->getCoreExtensions()) > 0;
    }

    protected function validate()
    {
        $type = ($this->engine->gteOc22() ? 'theme/' : 'module/') . $this->context->getBasename();
        if ($this->engine->gteOc23()) {
            $type = 'extension/' . $type;
        }

        if (!$this->user->hasPermission('modify', $type)) {
            $this->error['warning'] = $this->extension->translate('error_permission');
        }

        return empty($this->error);
    }

    /**
     * @return Theme_Admin_CategoryModel
     */
    protected function getCategoryModel()
    {
        return $this->getModel('category');
    }

    /**
     * @return Theme_Admin_LayoutBuilderModel
     */
    protected function getLayoutBuilderModel()
    {
        return $this->getModel('layoutBuilder');
    }
}