<?php

class Theme_Admin_StoreController extends TB_AdminController
{
    public function categorySettings()
    {
        $id = (string) $this->request->get['category_id'];
        $store_settings = $this->themeData->theme_settings['store']['category'];

        if (isset($store_settings[$id])) {
            $category_settings = $store_settings[$id];
        } else {
            $category_settings = array();
            $this->extension->getPlugin('store')->initCategorySettings($category_settings);
        }

        $this->data['store_category'] = $category_settings;

        $this->renderTemplate('theme_store_category');
    }

    public function deleteCategorySettings()
    {
        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $id = (string) $this->request->get['category_id'];
        if ($id == 'global') {
            return $this->sendJsonError("Cannot delete global settings");
        }

        $theme_settings = $this->getThemeModel()->getSettings();
        if (isset($theme_settings['store']['category'][$id])) {
            unset($theme_settings['store']['category'][$id]);
            $this->getThemeModel()->setAndPersistSettings($theme_settings);
        }

        return $this->sendJsonSuccess("The category settings have been deleted");
    }
}