<?php

class Seo_Admin_DefaultController extends TB_AdminController
{
    /**
     * @var Seo_Admin_DefaultModel
     */
    protected $defaultModel;

    /** @var  Seo_Admin_GeneratorModel */
    protected $generatorModel;

    public function init()
    {
        $this->defaultModel   = $this->getModel('default');
        $this->generatorModel = $this->getModel('generator');
    }

    public function index()
    {
        $this->data['seo']                  = $this->defaultModel->getSettings();
        $this->data['seo_general']          = $this->defaultModel->getGeneralSettings();
        $this->data['generator_items_data'] = $this->generatorModel->getGeneratorItemsData();
        $this->data['has_stories']          = $this->engine->getOcConfig('stories_settings');
        $this->data['languages']            = $this->engine->getAllLanguages();
        $this->data['seo_url_enabled']      = $this->engine->getOcConfig('config_seo_url');

        if (count($this->themeData->enabled_languages) > 1) {
            foreach (array_keys($this->themeData->enabled_languages) as $language_code) {
                if (empty($this->data['seo']['language_prefix_codes'][$language_code])) {
                    $this->data['seo']['language_prefix_codes'][$language_code] = $language_code;
                }
            }
        }

        $this->defaultModel->updateOcIndexes();

        $this->renderTemplate('index');
    }

    public function editor()
    {
        $item = $this->getArrayKey('item', $this->request->get, 'product');

        foreach ($this->engine->getAllLanguages() as $language) {
            $this->data['editor_' . $language['code']] = $this->getEditorLanguage($item, $language);
        }

        $this->data['item']      = $item;
        $this->data['languages'] = $this->engine->getAllLanguages();

        $this->renderTemplate('editor');
    }

    public function editorPage()
    {
        $item = $this->getArrayKey('item', $this->request->get, 'product');
        $language_code = $this->request->get['language_code'];

        $this->setOutput($this->getEditorLanguage($item, $this->engine->getLanguageByCode($language_code)));
    }

    protected function getEditorLanguage($item, $language)
    {
        $page = $this->getArrayKey('page', $this->request->get, 1);
        $pagination_limit = $this->getArrayKey('pagination_limit', $this->request->get, 15);
        $cells = $this->generatorModel->getDisplayFields($item);
        $general_settings = $this->defaultModel->getGeneralSettings();

        $filter_data = array(
            'original_language_id' => $general_settings['original_language']['id'],
            'start'                => ($page - 1) * $pagination_limit,
            'limit'                => $pagination_limit,
            'search_string'        => $this->getArrayKey('search_string', $this->request->get, ''),
            'cells'                => $cells
        );
        if ($item != 'manufacturer') {
            $filter_data['language_id'] = $language['id'];
        }

        $records = $this->generatorModel->getRecords($item, array(), $filter_data);
        if ($general_settings['original_language']['id'] == $language['id']) {
            $existing_keywords = $this->engine->getDbHelper()->getPairs('url_alias', 'query', 'keyword');
        } else {
            $existing_keywords = $this->engine->getDbHelper()->getPairs(
                'burnengine_url_alias',
                'query',
                'keyword',
                array('language_id' => $language['id'])
            );
        }

        foreach ($records as &$record) {
            $query = $item . '_id=' . $record['id'];
            $record['seo_keyword'] = isset($existing_keywords[$query]) ? $existing_keywords[$query] : '';
        }

        $view_data = array();

        $view_data['records'] = $records;
        $view_data['total_records'] = $this->generatorModel->getRecords($item, array(), $filter_data, true);
        $view_data['editor_language'] = $language;
        $view_data['item'] = $item;
        $view_data['cells'] = $cells;

        $url_data = TB_FormHelper::initFlatVarsSimple(array(
            'filter_search' => null,
            'language_code' => $language['code'],
            'page'          => $page,
            'item'          => $item
        ), $this->request->get);

        $request_url = $this->buildUrl($url_data, array('filter_search', 'language_code', 'item'));

        $translation = $this->engine->loadOcTranslation();

        $pagination = new Pagination();
        $pagination->total = $view_data['total_records'];
        $pagination->page  = $page;
        $pagination->limit = $pagination_limit;
        $pagination->text  = $translation['text_pagination'];
        $pagination->url   = $this->tbUrl->generate('default/editorPage', 'page={page}&' . $request_url);

        $view_data = array_merge($view_data, $url_data);
        $view_data['pagination'] = $pagination->render();
        $view_data['filter_request_url'] = $this->tbUrl->generate('default/editor', $request_url);

        return $this->fetchTemplate('editor_item', array_merge($this->data, $view_data));
    }

    public function editField()
    {
        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $general_settings = $this->defaultModel->getGeneralSettings();
        $field            = (string) $this->request->post['name'];
        $language_id      = (int) $this->request->post['language_id'];
        $item             = (string) $this->request->post['item'];
        $id               = (int) $this->request->post['pk'];

        if ($field == 'seo_keyword') {
            $this->generatorModel->updateSeoKeywordRecord($item, $id, (string) $this->request->post['value'], $language_id, $general_settings);
        } else {
            $this->generatorModel->updateMetaRecord($item, $id, $field, (string) $this->request->post['value'], $language_id);
        }

        return $this->sendJsonSuccess('The record has been updated');
    }

    public function saveSettings()
    {
        if ($this->request->server['REQUEST_METHOD'] != 'POST' || !$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        if (empty($this->request->post['seo'])) {
            return $this->sendJsonError('Empty data!');
        }

        $this->defaultModel->saveSettings($this->request->post['seo']);
        $this->defaultModel->saveGeneralSettings($this->request->post['seo_general']);

        return $this->sendJsonSuccess('The slider has been saved');
    }

    public function preview()
    {
        $context = $this->getArrayKey('context', $this->request->get);
        $item = $this->getArrayKey('item', $this->request->get);

        if (!method_exists($this, TB_Utils::camelize($context) . 'Preview')) {
            return $this->sendJsonError('Invalid operation');
        }

        if (!isset($this->request->get['seo_general'][$item][$context])) {
            return $this->sendJsonError('Invalid settings');
        }

        return $this->{TB_Utils::camelize($context) . 'Preview'}($item, $this->request->get['seo_general'][$item][$context]);
    }

    public function generate()
    {
        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $context = $this->getArrayKey('context', $this->request->get);
        $item = $this->getArrayKey('item', $this->request->get);

        if (!method_exists($this, TB_Utils::camelize($context) . 'Generate')) {
            return $this->sendJsonError('Invalid operation');
        }

        if (!isset($this->request->get['seo_general'][$item][$context])) {
            return $this->sendJsonError('Invalid settings');
        }

        return $this->{TB_Utils::camelize($context) . 'Generate'}($item, $this->request->get['seo_general'][$item][$context]);
    }

    public function clear()
    {
        if (!$this->validate()) {
            return $this->sendJsonError($this->error['warning']);
        }

        $context = $this->getArrayKey('context', $this->request->get);
        $item = $this->getArrayKey('item', $this->request->get);
        $general_settings = $this->defaultModel->getGeneralSettings();
        $dbHelper = $this->engine->getDbHelper();
        $languages = $this->engine->getAllLanguages();

        if (!empty($this->request->get['seo_general'][$item][$context]['languages'])) {
            $form_languages = $this->request->get['seo_general'][$item][$context]['languages'];
        } else {
            // The manufacturers have no language
            $form_languages = array($general_settings['original_language']['code'] => 1);
        }

        foreach ($form_languages as $language_code => $value) {
            if (empty($value)) {
                continue;
            }
            if ($context == 'seo_keyword') {
                $query = (string) $item . '_id=%';
                if ($general_settings['original_language']['code'] == $language_code) {
                    $dbHelper->deleteRecord('url_alias', '`query` LIKE ' . $dbHelper->quote($query));
                } else {
                    $dbHelper->deleteRecord('burnengine_url_alias', 'language_id = ' . (int) $languages[$language_code]['id'] . ' AND `query` LIKE ' . $dbHelper->quote($query));
                }
            } else {
                $dbHelper->updateRecord($item . '_description', array($context => ''));
            }
        }

        return $this->sendJsonSuccess('The items have been cleared');
    }

    protected function seoKeywordPreview($item, $settings)
    {
        $data = $this->generatorModel->buildSeoKeywords($item, $settings, $this->defaultModel->getGeneralSettings(), true);

        return $this->sendJsonSuccess('The data has been generated', array(
            'preview_data' => $data['insert_data'],
            'affected'     => $data['affected'],
            'languages'    => $this->getLanguagesGroupedById()
        ));
    }

    protected function seoKeywordGenerate($item, $settings)
    {
        $data = $this->generatorModel->buildSeoKeywords($item, $settings, $this->defaultModel->getGeneralSettings());

        return $this->sendJsonSuccess('The data has been generated', array(
            'affected' => $data['affected']
        ));
    }

    protected function h1HeadingPreview($item, $settings)
    {
        $data = $this->generatorModel->buildHeadings($item, $settings);

        return $this->sendJsonSuccess('The data has been generated', array(
            'preview_data' => $data['insert_data'],
            'affected'     => $data['affected'],
            'languages'    => $this->getLanguagesGroupedById()
        ));
    }

    protected function metaTitlePreview($item, $settings)
    {
        $data = $this->generatorModel->buildMetaTitles($item, $settings, true);

        return $this->sendJsonSuccess('The data has been generated', array(
            'preview_data' => $data['update_data'],
            'affected'     => $data['affected'],
            'languages'    => $this->getLanguagesGroupedById()
        ));
    }

    protected function metaTitleGenerate($item, $settings)
    {
        $data = $this->generatorModel->buildMetaTitles($item, $settings);

        return $this->sendJsonSuccess('The meta information fields have been generated', array(
            'affected' => $data['affected']
        ));
    }

    protected function metaDescriptionPreview($item, $settings)
    {
        $data = $this->generatorModel->buildMetaDescriptions($item, $settings, true);

        return $this->sendJsonSuccess('The meta information fields have been generated', array(
            'preview_data' => $data['update_data'],
            'affected'     => $data['affected'],
            'languages'    => $this->getLanguagesGroupedById()
        ));
    }

    protected function metaDescriptionGenerate($item, $settings)
    {
        $data = $this->generatorModel->buildMetaDescriptions($item, $settings);

        return $this->sendJsonSuccess('The meta information fields have been generated', array(
            'affected' => $data['affected']
        ));
    }

    protected function metaKeywordPreview($item, $settings)
    {
        $data = $this->generatorModel->buildMetaKeywords($item, $settings, true);

        return $this->sendJsonSuccess('The meta information fields have been generated', array(
            'preview_data' => $data['update_data'],
            'affected'     => $data['affected'],
            'languages'    => $this->getLanguagesGroupedById()
        ));
    }

    protected function metaKeywordGenerate($item, $settings)
    {
        $data = $this->generatorModel->buildMetaKeywords($item, $settings);

        return $this->sendJsonSuccess('The meta information fields have been generated', array(
            'affected' => $data['affected']
        ));
    }

    public function checkPermissions()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        return $this->sendJsonSuccess('Success');
    }

    protected function getLanguagesGroupedById()
    {
        $languages = array();
        foreach ($this->engine->getAllLanguages() as $language) {
            $languages[$language['id']] = $language;
        }

        return $languages;
    }
}