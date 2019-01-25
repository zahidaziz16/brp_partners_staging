<?php

if (!class_exists('URLify')) {
    require_once TB_THEME_ROOT . '/library/vendor/URLify.php';
}

if (!class_exists('Html2Text')) {
    require_once TB_THEME_ROOT . '/library/vendor/Html2Text.php';
}

require_once dirname(__FILE__) . '/../../model/generatorModel.php';

class Seo_Admin_GeneratorModel extends Seo_GeneratorModel
{
    protected $existing_keywords = array();

    public function buildSeoKeywords($item, array $settings, array $general_settings, $preview = false)
    {
        $affected = array('total' => array());

        if (empty($settings['languages'])) {
            $settings['languages'] = array(
                $this->engine->getDefaultCatalogLanguage('code') => 1
            );
        }

        $original_language_id = $general_settings['original_language']['id'];
        if (empty($settings['languages']) || isset($settings['languages'][$general_settings['original_language']['code']])) {
            $this->existing_keywords[$original_language_id] = $this->dbHelper->getPairs('url_alias', 'keyword', 'query');
            $affected[$original_language_id] = 0;
        }

        $languages = array();

        foreach ($this->engine->getAllLanguages() as $language) {
            if (empty($settings['languages'][$language['code']])) {
                continue;
            }

            $languages[$language['id']] = $language;
            if ($general_settings['multilingual_keyword'] && $language['id'] != $original_language_id && isset($settings['languages'][$language['code']])) {
                $this->existing_keywords[$language['id']] = (array) $this->dbHelper->getPairs(
                    'burnengine_url_alias',
                    'keyword', 'query',
                    array('language_id' => $language['id'])
                );
                $affected[$language['id']] = 0;
            }
        }

        if (!$table = $this->getTableData($item)) {
            return array(
                'insert_data' => array(),
                'affected'    => 0
            );
        }

        if (!$preview) {
            $insert_data = $items_query = array(
                'original'   => array(),
                'additional' => array()
            );
            foreach ($languages as $language) {
                $items_query['additional'][$language['id']] = array();
            }

        } else {
            $insert_data = $items_query = array();
        }


        $has_languages = $item != $table['lang_table'];
        $insert_fields = array('query', 'keyword');
        $extract_text_data = $this->getItemExtractTextData($item);

        foreach ($this->getRecords($table, $has_languages ? $languages : $original_language_id) as $record) {

            if (!isset($this->existing_keywords[$record['language_id']])) {
                continue;
            }

            $query = $table['primary_key'] . '=' . $record['id'];

            $all_keywords = $this->existing_keywords[$record['language_id']];
            $old_keyword = array_search($query, $all_keywords);

            if (false !== $old_keyword && $settings['skip_existing']) {
                continue;
            }

            $keyword = $this->{'extract' . ucfirst($item) . 'Text'}($record, $extract_text_data, $settings['pattern']);
            $keyword = $this->slugify($keyword);
            if ($settings['transliterate']) {
                $keyword = URLify::filter($keyword, 256, ($has_languages ? $languages[$record['language_id']]['code'] : ''));
            }

            $postfix = '';
            $i = 0;
            while (array_key_exists($keyword . $postfix, $all_keywords) && $all_keywords[$keyword . $postfix] != $query) {
                $postfix = '-' . ++$i;
            }
            $keyword .= $postfix;

            if (isset($all_keywords[$keyword]) && ($all_keywords[$keyword] == $query)) {
                continue;
            }

            $this->existing_keywords[$record['language_id']][$keyword] = $query;
            $new_record = array($query, $keyword);

            if (!$preview) {
                if ($record['language_id'] == $original_language_id) {
                    $insert_data['original'][] = $new_record;
                    $items_query['original'][] = $query;
                } else {
                    array_push($new_record, (int) $record['language_id']);
                    $insert_data['additional'][] = $new_record;
                    $items_query['additional'][$record['language_id']][] = $query;
                }
            } else
            if (!isset($insert_data[$record['language_id']]) || count($insert_data[$record['language_id']]) < 10) {
                $insert_data[$record['language_id']][$record['id']] = array(
                    ucfirst($item) . ' Name' => $record['lang_field'],
                    'Keyword'                => $keyword
                );
            }

            $affected['total'][] = $record['id'];
            $affected[$record['language_id']]++;
        }

        if (!$preview) {
            if (!empty($insert_data['original'])) {
                $sql = 'DELETE FROM ' . DB_PREFIX . 'url_alias WHERE query IN("' . implode('","', array_unique($items_query['original'])) . '")';
                $this->db->query($sql);
                $this->dbHelper->addMultipleRecords('url_alias', $insert_fields, $insert_data['original']);
            }

            if (!empty($insert_data['additional'])) {
                foreach ($items_query['additional'] as $language_id => $query_items) {
                    $sql = 'DELETE FROM ' . DB_PREFIX . 'burnengine_url_alias WHERE language_id = ' . (int) $language_id . ' AND query IN("' . implode('","', $query_items) . '")';
                    $this->db->query($sql);
                }
                if ($has_languages) {
                    array_push($insert_fields, 'language_id');
                }
                $this->dbHelper->addMultipleRecords('burnengine_url_alias', $insert_fields, $insert_data['additional']);
            }
        }

        $affected['total'] = count(array_unique($affected['total']));

        return array(
            'insert_data' => $insert_data,
            'affected'    => $affected
        );
    }

    public function buildHeadings($item, array $settings)
    {
        if (empty($settings['pattern'])) {
            return array(
                'update_data' => array(),
                'affected'    => 0
            );
        }

        if (empty($settings['languages'])) {
            $settings['languages'] = array(
                $this->engine->getDefaultCatalogLanguage('code') => 1
            );
        }


        $extract_text_data = $this->getItemExtractTextData($item);
        $table_data = $this->getTableData($item);
        $update_data = array();
        $affected = array('total' => array());

        $language_ids = array();
        foreach ($this->engine->getAllLanguages() as $language) {
            if ($settings['languages'][$language['code']]) {
                $language_ids[$language['language_id']] = $language['code'];
                $affected[$language['language_id']] = 0;
            }
        }

        foreach ($this->getRecords($table_data, $language_ids) as $row) {

            $description_html = new Html2Text(html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8'));
            $row['description'] = preg_replace('/\s+/S', " ", $description_html->getText());

            $title = $this->{'extract' . ucfirst($item) . 'Text'}($row, $extract_text_data, $settings['pattern']);
            $id = $row[$table_data['primary_key']];

            if (!isset($update_data[$row['language_id']]) || count($update_data[$row['language_id']]) < 10) {
                $update_data[$row['language_id']][$id] = array(
                    ucfirst($item) . ' Name' => $row[$table_data['lang_field']],
                    'H1 Heading'             => $title
                );

                $affected['total'][] = $row['id'];
                $affected[$row['language_id']]++;
            }
        }

        $affected['total'] = count(array_unique($affected['total']));

        return array(
            'insert_data' => $update_data,
            'affected'    => $affected
        );
    }

    public function buildMetaTitles($item, array $settings, $preview = false)
    {
        return $this->buildMetaFields($item, $settings, 'title', $preview);
    }

    public function buildMetaDescriptions($item, array $settings, $preview = false)
    {
        return $this->buildMetaFields($item, $settings, 'description', $preview);
    }

    public function buildMetaKeywords($item, array $settings, $preview = false)
    {
        return $this->buildMetaFields($item, $settings, 'keyword', $preview);
    }

    protected function buildMetaFields($item, array $settings, $type, $preview = false)
    {
        if (empty($settings['pattern'])) {
            return array(
                'update_data' => array(),
                'affected'    => 0
            );
        }

        if (empty($settings['languages'])) {
            $settings['languages'] = array(
                $this->engine->getDefaultCatalogLanguage('code') => 1
            );
        }

        $extract_text_data = $this->getItemExtractTextData($item);
        $description_patterns = $this->extractDescriptionPatterns($settings['pattern']);
        $table_data = $this->getTableData($item);
        $update_data = array();
        $affected = array('total' => array());

        $language_ids = array();
        foreach ($this->engine->getAllLanguages() as $language) {
            if ($settings['languages'][$language['code']]) {
                $language_ids[$language['language_id']] = $language['code'];
                $affected[$language['id']] = 0;
            }
        }

        foreach ($this->getRecords($table_data, $language_ids) as $row) {

            if (!empty($row['meta_' . $type]) && $settings['skip_existing']) {
                continue;
            }

            $description_html = new Html2Text(html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8'));
            $row['description'] = preg_replace('/\s+/S', " ", $description_html->getText());
            $converted_patterns = $this->convertParsedDescriptionPatterns($row['description'], $description_patterns, $this->getStopWords($row['language_id']));

            $meta_info = $this->{'extract' . ucfirst($item) . 'Text'}($row, $extract_text_data, $settings['pattern'], $converted_patterns);
            if (empty($meta_info)) {
                continue;
            }
            $id = $row[$table_data['primary_key']];

            $affected['total'][] = $id;
            $affected[$row['language_id']]++;

            if (!$preview) {
                $update_data[] = array(array('meta_' . $type => $meta_info), array(
                    $table_data['primary_key']  => $id,
                    'language_id'               => $row['language_id']
                ));
            } else
            if (!isset($update_data[$row['language_id']]) || count($update_data[$row['language_id']]) < 10) {
                $update_data[$row['language_id']][$id] = array(
                    ucfirst($item) . ' Name' => $row[$table_data['lang_field']],
                    'Meta ' . ucfirst($type) => $meta_info
                );
            }
        }

        if (!$preview) {
            foreach ($update_data as $record_data) {
                $this->dbHelper->updateRecord($table_data['lang_table'], $record_data[0], $record_data[1]);
            }
        }

        $affected['total'] = count(array_unique($affected['total']));

        return array(
            'update_data' => $update_data,
            'affected'    => $affected
        );
    }

    protected function getItemExtractTextData($item)
    {
        /** @var Theme_Admin_CategoryModel $categoryModel */
        $categoryModel = $this->getModel('category');

        switch ($item) {
            case 'product':
                $categories = array();
                foreach ($this->engine->getAllLanguages() as $language) {
                    $categories[$language['id']] = $categoryModel->buildCategoriesFlatTree($language['id'], true, false);
                }
                $extract_text_data = array(
                    'manufacturers'       => $this->dbHelper->getPairs('manufacturer', 'manufacturer_id', 'name'),
                    'categories'          => $categories,
                    'product_to_category' => $this->dbHelper->getGroup('product_to_category', array('product_id' => 'category_id'))
                );
                break;
            case 'category':
                $categories = array();
                foreach ($this->engine->getAllLanguages() as $language) {
                    $categories[$language['id']] = $categoryModel->buildCategoriesFlatTree($language['id'], true, false);
                }
                $extract_text_data = array(
                    'all_categories' => $categories
                );
                break;
            default:
                $extract_text_data = array();
        }

        return $extract_text_data;
    }

    protected function extractDescriptionPatterns($string)
    {
        $description_patterns = array();
        if (preg_match_all('/\[description:(.*?)\]/', $string, $matches)) {
            foreach ($matches[0] as $match_index => $match) {
                if (preg_match('/([a-z_]+):([0-9]+)/', $match, $submatches)) {
                    $description_patterns[$match] = array($submatches[1] => $submatches[2]);
                }
            }
        }

        return $description_patterns;
    }

    protected function getStopWords($language_id)
    {
        static $stopwords = null;


        if (null === $stopwords) {
            $stopwords = array();
            foreach ($this->engine->getAllLanguages() as $language) {
                $languages[$language['id']] = $language;
                $stopwords_file = $this->context->getEngineDir() . '/admin/language/' . $language['directory'] . '/stopwords.txt';
                if (is_file($stopwords_file)) {
                    $stopwords[$language['id']] = explode(',', file_get_contents($stopwords_file));
                }
            }
        }


        return isset($stopwords[$language_id]) ? $stopwords[$language_id] : array();
    }

    /**
     * @param $product
     * @param $data
     * @param $main_pattern
     * @param $description_patterns
     * @return mixed
     */
    protected function extractProductText($product, $data, $main_pattern, array $description_patterns = array())
    {
        $category_names = array();

        if (isset($data['product_to_category'][$product['product_id']])) {
            foreach ($data['product_to_category'][$product['product_id']] as $category_id) {
                if (!isset($data['categories'][$product['language_id']][$category_id])) {
                    continue;
                }
                $category_names[] = $data['categories'][$product['language_id']][$category_id]['name'];
            }
        }

        return trim(str_replace(
            array_merge(array('[name]', '[model]', '[sku]', '[upc]', '[manufacturer]', '[category]', '[categories]', '[description]'), array_keys($description_patterns)),
            array_merge(array(
                $product['name'],
                $product['model'],
                $product['sku'],
                $product['upc'],
                isset($data['manufacturers'][$product['manufacturer_id']]) ? $data['manufacturers'][$product['manufacturer_id']] : '',
                !empty($category_names) ? $category_names[0] : '',
                !empty($category_names) ? implode(' / ', $category_names) : '',
                $product['description']
            ), array_values($description_patterns)),
            $main_pattern
        ));
    }

    protected function extractCategoryText($category, $data, $main_pattern, array $description_patterns = array())
    {
        return trim(str_replace(
            array_merge(array('[name]', '[parent_name]', '[description]'), array_keys($description_patterns)),
            array_merge(array(
                $category['name'],
                !empty($category['parent_id']) ? $data['all_categories'][$category['language_id']][$category['parent_id']]['name'] : '',
                $category['description']
            ), array_values($description_patterns)),
            $main_pattern
        ));
    }

    protected function extractInformationText($information, $data, $main_pattern, array $description_patterns = array())
    {
        return trim(str_replace(
            array_merge(array('[title]', '[description]'), array_keys($description_patterns)),
            array_merge(array(
                $information['title'],
                $information['description']
            ), array_values($description_patterns)),
            $main_pattern
        ));
    }

    protected function extractManufacturerText($manufacturer, $data, $main_pattern)
    {
        return trim(str_replace('[name]', $manufacturer['name'], $main_pattern));
    }

    protected function extractStoryText($story, $data, $main_pattern)
    {
        return trim(str_replace('[title]', $story['title'], $main_pattern));
    }

    protected function convertParsedDescriptionPatterns($description, $parsed_patterns, $stopwords)
    {
        if  (!$parsed_patterns) {
            return array();
        }

        foreach ($parsed_patterns as $pattern => $pattern_values) {
            $limit_type = key($pattern_values);
            $filter_stop_words = true;
            switch ($limit_type) {
                case 'chars':
                    $width = (int) current($pattern_values);
                    if (utf8_strlen($description) > $width) {
                        $parsed_patterns[$pattern] = utf8_substr($description, 0, utf8_strpos($description, ' ', $width));
                    } else {
                        $parsed_patterns[$pattern] = $description;
                    }
                    break;
                case 'words_all':
                    $filter_stop_words = false;
                case 'words':
                    $words = explode(' ', preg_replace('~[^\p{L}\p{N}]++~u', ' ', $description));
                    if ($filter_stop_words) {
                        foreach ($words as $word_key => $word) {
                            if (utf8_strlen($word) <= 2 || ($filter_stop_words && $stopwords && in_array($word, $stopwords))) {
                                unset($words[$word_key]);
                            }
                        }
                    }
                    $parsed_patterns[$pattern] = implode(' ', array_slice($words, 0, (int) current($pattern_values)));
                    break;
                default:
                    unset($parsed_patterns[$pattern]);
            }
        }

        return $parsed_patterns;
    }

    public function getGeneratorItemsData()
    {
        $items = array();
        foreach (array('product', 'category', 'information') as $item) {
            $items[$item] = array(
                'seo_keyword' => array(
                    'item'         => $item,
                    'context'      => 'seo_keyword',
                    'title'        => 'SEO Urls',
                    'preview_only' => false
                ),
                'h1_heading' => array(
                    'item'         => $item,
                    'context'      => 'h1_heading',
                    'title'        => 'H1 Heading',
                    'preview_only' => true
                ),
                'meta_title' => array(
                    'item'         => $item,
                    'context'      => 'meta_title',
                    'title'        => 'Meta Title',
                    'preview_only' => false
                ),
                'meta_description' => array(
                    'item'         => $item,
                    'context'      => 'meta_description',
                    'title'        => 'Meta Description',
                    'preview_only' => false
                ),
                'meta_keyword' => array(
                    'item'         => $item,
                    'context'      => 'meta_keyword',
                    'title'        => 'Meta Keyword',
                    'preview_only' => false
                ),
            );
        }

        $items['product']['seo_keyword']['vars'] = $items['product']['meta_title']['vars'] = $items['product']['h1_heading']['vars'] = array('name', 'category', 'manufacturer', 'model', 'sku', 'upc');
        $items['product']['meta_description']['vars'] = $items['product']['meta_keyword']['vars'] = array('name', 'category', 'categories', 'manufacturer', 'model', 'sku', 'upc', 'description', 'description:chars:80', 'description:words:5', 'description:words_all:10');

        $items['category']['seo_keyword']['vars'] = $items['category']['meta_title']['vars'] = $items['category']['h1_heading']['vars'] = array('name', 'parent_name');
        $items['category']['meta_description']['vars'] = $items['category']['meta_keyword']['vars'] = array('name', 'parent_name', 'description', 'description:chars:80', 'description:words:5', 'description:words_all:10');

        $items['information']['seo_keyword']['vars'] = $items['information']['meta_title']['vars'] = $items['information']['h1_heading']['vars'] = array('title');
        $items['information']['meta_description']['vars'] = $items['information']['meta_keyword']['vars'] = array('title', 'description', 'description:chars:80', 'description:words:5', 'description:words_all:10');

        $items['manufacturer'] = array(
            'seo_keyword' => array(
                'item'           => 'manufacturer',
                'context'        => 'seo_keyword',
                'title'          => 'SEO Urls',
                'preview_only'   => false,
                'vars'           => array('name'),
                'skip_languages' => true
            )
        );

        $items['story'] = array(
            'seo_keyword' => array(
                'item'           => 'story',
                'context'        => 'seo_keyword',
                'title'          => 'SEO Urls',
                'preview_only'   => false,
                'vars'           => array('title')
            )
        );

        return $items;
    }

    public function insertItemLanguageKeywords($data, $entity, $entity_id)
    {
        /** @var Seo_Admin_DefaultModel $default_model */
        $default_model = $this->extension->getModel('default');
        $settings = $default_model->getGeneralSettings();

        if (!isset($settings[$entity]['seo_keyword']) || empty($settings[$entity]['seo_keyword']['autofill'])) {
            return $data;
        }

        $entity_settings = $settings[$entity]['seo_keyword'];

        $languages = $this->engine->getAllLanguages();
        $table_data = $this->getTableData($entity);
        $extract_text_data = $this->getItemExtractTextData($entity);

        if ($entity_settings['autofill'] && !empty($entity_settings['languages'][$settings['original_language']['code']]) && (empty($data['keyword']) || !$entity_settings['skip_existing'])) {

            if (!isset($languages[$settings['original_language']['code']])) {
                throw new Exception('The original language does not exist: ' . $settings['original_language']['name']);
            }

            $original_language_id = $languages[$settings['original_language']['code']]['language_id'];
            $lang_prop = $this->getLanguagePropertyPath($data, $table_data['lang_field_prop'], $original_language_id);

            if (isset($lang_prop[$table_data['lang_field']])) {
                $record = array_merge($data, $lang_prop);
                $record['language_id'] = $original_language_id;
                $record[$entity . '_id'] = $entity_id;
                $data['keyword'] = $this->{'extract' . ucfirst($entity) . 'Text'}($record, $extract_text_data, $entity_settings['pattern']);
                $data['keyword'] = $this->slugify($data['keyword']);
                if ($entity_settings['transliterate']) {
                    $data['keyword'] = URLify::filter($data['keyword'], 256, $settings['original_language']['code']);
                }
            }
        }

        if (empty($data['language_keyword'])) {
            return $data;
        }

        if ($entity_settings['autofill'] || !$entity_settings['skip_existing']) {
            foreach ($data['language_keyword'] as $language_id => $keyword) {
                $language = $this->engine->getLanguageById($language_id);
                $lang_prop = $this->getLanguagePropertyPath($data, $table_data['lang_field_prop'], $language_id);

                if (isset($lang_prop[$table_data['lang_field']]) && !empty($entity_settings['languages'][$language['code']]) && (empty($keyword) || !$entity_settings['skip_existing'])) {
                    $record = array_merge($data, $lang_prop);
                    $record['language_id'] = $language_id;
                    $record[$entity . '_id'] = $entity_id;
                    $keyword = $this->{'extract' . ucfirst($entity) . 'Text'}($record, $extract_text_data, $entity_settings['pattern']);
                    $keyword = $this->slugify($keyword);
                    if ($entity_settings['transliterate']) {
                        $keyword = URLify::filter($keyword, 256, $settings['original_language']['code']);
                    }
                    $data['language_keyword'][$language_id] = $keyword;
                }
            }
        }

        $query = $entity . '_id' . '=' . $entity_id;
        $this->dbHelper->deleteRecord('burnengine_url_alias', array('query' => $query));

        foreach ($data['language_keyword'] as $language_id => $keyword) {
            if (!empty($keyword)) {
                $this->dbHelper->addRecord('burnengine_url_alias', array(
                    'language_id' => $language_id,
                    'query'       => $query,
                    'keyword'     => $keyword
                ));
            }
        }

        return $data;
    }

    protected function getLanguagePropertyPath($data, $property, $language_id)
    {
        $reference = null;

        foreach (explode('.', $property) as $item) {
            if ($item == '{language_id}') {
                $item = $language_id;
            }

            if (null === $reference && isset($data[$item])) {
                $reference = &$data[$item];
            } else
            if (isset($reference[$item])) {
               $reference = &$reference[$item];
            }

            if ($item == $language_id) {
                break;
            }
        }

        return $reference;
    }

    public function getItemLanguageKeywords($entity_id_name = null, $recurse = false)
    {
        $request = $this->engine->getOcRequest();
        $query = '';

        if (null === $entity_id_name) {
            foreach (array('product', 'category', 'manufacturer', 'information') as $name) {
                if (!empty($request->get[$name . '_id'])) {
                    $entity_id_name = $name . '_id';

                    break;
                }
            }
        }

        if (!empty($request->get[$entity_id_name])) {
            $query = $entity_id_name . '=' . (string) $request->get[$entity_id_name];
        }

        if (!$keywords = $this->dbHelper->getRecords('burnengine_url_alias', array('query' => $query), array('index_field' => 'language_id'))) {
            $keywords = array();
        }

        /** @var Seo_Admin_DefaultModel $default_model */
        $default_model = $this->extension->getModel('default');
        $config_general = $default_model->getGeneralSettings();
        $result = array(
            'original'   => array(),
            'additional' => array()
        );

        foreach ($this->engine->getAllLanguages(false) as $language) {
            if (!$language['status'] && $language['language_id'] != $config_general['original_language']['language_id']) {
                continue;
            }

            $language = array_merge(
                $language,
                isset($keywords[$language['language_id']]) ? $keywords[$language['language_id']] : array(
                    'query'   => $query,
                    'keyword' => ''
                )
            );

            if ($language['language_id'] == $config_general['original_language']['language_id']) {
                $result['original'] = $language;
            } else {
                $result['additional'][$language['language_id']] = $language;
            }
        }

        if (empty($result['original'])) {
            if ($recurse) {
                throw new RuntimeException('Missing original language ' . var_export($result, true));
            }

            $additional_language = reset($result['additional']);
            $records = $this->dbHelper->getRecords('burnengine_url_alias', array(
                'language_id' => $additional_language['language_id']
            ));

            $new_records = array();
            foreach ($records as $record) {
                $new_records[] = array(
                    'query'   => $record['query'],
                    'keyword' => $record['keyword']
                );

                $this->engine->getDbHelper()->deleteRecord('url_alias', array('query' => $record['query']));
                $this->engine->getDbHelper()->deleteRecord('burnengine_url_alias', array('query' => $record['query']));
            }

            $this->engine->getDbHelper()->addMultipleRecords('url_alias', array('query', 'keyword'), $new_records);
            $config_general['original_language'] = $additional_language;

            $default_model->getSettingsModel(0)->setAndPersistScopeSettings('seo_general', $config_general);

            return $this->getItemLanguageKeywords($entity_id_name, true);
        }

        return $result;
    }

    public function getDisplayFields($item)
    {
        $table_data = $this->getTableData($item);

        if ($item == 'manufacturer') {
            return array($table_data['lang_field'], 'seo_keyword');
        }

        return array($table_data['lang_field'], 'seo_keyword', 'meta_title', 'meta_description');
    }

    public function getRecords($table, $languages, $data = array(), $total = false)
    {
        if (is_string($table)) {
            $table = $this->getTableData($table);
        }

        if (!$total) {
            $sql = 'SELECT
                    main_table.*' . ($table['main_table'] != $table['lang_table'] ? ',lang_table.*' : '') . ',
                    main_table.' . $table['primary_key'] . ' AS id,
                    ' . $table['lang_field'] . ' AS lang_field';
            if (!empty($languages)) {
                $sql .= ', ' . (is_array($languages) ? ' language_id' : $languages . ' AS language_id');
            }
        } else {
            $sql = 'SELECT COUNT(DISTINCT main_table.' . $table['primary_key'] . ') AS total';
        }

        $sql .= ' FROM ' . DB_PREFIX . $table['main_table'] . ' AS main_table';

        if ($table['main_table'] != $table['lang_table']) {
          $sql .= ' LEFT JOIN ' . DB_PREFIX . $table['lang_table'] . ' AS lang_table ON main_table.' . $table['primary_key'] . ' = lang_table.' . $table['primary_key'];
        }

        $sql .= ' WHERE 1';

        if (!empty($table['sql_cond'])) {
            $sql .= ' AND ' . $table['sql_cond'];
        }

        if ($languages && is_array($languages)) {
            $sql .= ' AND language_id IN (' . implode(',', array_keys($languages)) . ')';
        }

        if (!empty($data['language_id'])) {
            $sql .= ' AND language_id = ' . (int) $data['language_id'];
        }

        if (!empty($data['search_string'])) {
            $search_condition = array();
            foreach ($data['cells'] as $cell) {
                if ($cell != 'seo_keyword') {
                    $search_condition[] = $cell . ' LIKE "%' . $data['search_string'] . '%"';
                }
            }

            $sql .= ' AND
            (
                (' . implode(' OR ', $search_condition) . ')
                OR
                main_table.' . $table['primary_key'] . ' IN (
                  SELECT SUBSTRING_INDEX( query, "=", -1 ) AS item_id
                  FROM ' . DB_PREFIX . (empty($data['language_id']) || $data['language_id'] == $data['original_language_id'] ? '' : 'burnengine_') . 'url_alias
                  WHERE `keyword` LIKE "%' . $data['search_string'] . '%"' .
                  (empty($data['language_id']) || $data['language_id'] == $data['original_language_id'] ? '' : ' AND language_id = ' . (int) $data['language_id']) . '
                )
            )';
        }

        if (!$total && (isset($data['start']) || isset($data['limit']))) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        return !$total ? $this->db->query($sql)->rows : $this->db->query($sql)->row['total'];
    }

    public function updateSeoKeywordRecord($item, $id, $keyword, $language_id, $general_settings)
    {
        $table = $this->getTableData($item);
        $query = $table['primary_key'] . '=' . $id;
        $new_record = array(
            'query'   => $query,
            'keyword' => $keyword
        );

        if ($general_settings['original_language']['id'] == $language_id) {
            $this->dbHelper->deleteRecord('url_alias', array('query' => $query));
            if ($keyword) {
                $this->dbHelper->addRecord('url_alias', $new_record);
            }

        } else {
            $this->dbHelper->deleteRecord('burnengine_url_alias', array('query' => $query, 'language_id' => $language_id));
            $new_record['language_id'] = $language_id;
            if ($keyword) {
                $this->dbHelper->addRecord('burnengine_url_alias', $new_record);
            }
        }
    }

    public function updateMetaRecord($item, $id, $field, $value, $language_id)
    {
        $table = $this->getTableData($item);
        $this->dbHelper->updateRecord($table['lang_table'], array($field => $value), array($table['primary_key'] => $id, 'language_id' => $language_id));
    }

    public function slugify($text)
    {
        $text = strip_tags(html_entity_decode($text, ENT_COMPAT, "UTF-8"));
        $text = preg_replace('/&.+?;/', '', $text);
        $text = preg_replace ('/^\s+|\s+$/', '', $text);
        $text = preg_replace ('/[-\s]+/', '-', $text);
        $text = preg_replace("/[^[:alnum][:space]]/ui", '', $text);

        return $this->expandString(strtolower($text));
    }

    protected static function expandString($string)
    {
        $string = self::expandCurrencies($string);
        $string = self::expandSymbols($string);

        return $string;
    }

    /**
     * Expands the numeric currencies in euros, dollars, pounds
     * and yens that the given string may include.
     */

    private static function expandCurrencies($string)
    {
        return preg_replace(
            array(
                '/(\s|^)(\d*)\€(\s|$)/',
                '/(\s|^)\$(\d*)(\s|$)/',
                '/(\s|^)\£(\d*)(\s|$)/',
                '/(\s|^)\¥(\d*)(\s|$)/',
                '/(\s|^)(\d+)\.(\d+)\€(\s|$)/',
                '/(\s|^)\$(\d+)\.(\d+)(\s|$)/',
                '/(\s|^)£(\d+)\.(\d+)(\s|$)/',
            ),
            array(
                ' \2 euros ',
                ' \2 dollars ',
                ' \2 pounds ',
                ' \2 yen ',
                ' \2 euros \3 cents ',
                ' \2 dollars \3 cents ',
                ' \2 pounds \3 pence ',
            ),
            $string
        );
    }

    /**
     * Expands the special symbols that the given string
     * may include, such as '@', '.', '#' and '%'.
     */

    private static function expandSymbols($string)
    {
        return preg_replace(
            array(
                '/\s*&\s*/',
                '/\s*#/',
                '/\s*@\s*/',
                '/(\S|^)\.(\S)/',
                '/\s*\*\s*/',
                '/\s*%\s*/',
                '/(\s*=\s*)/',
            ),
            array(
                ' and ',
                ' number ',
                ' at ',
                '$1 dot $2',
                ' star ',
                ' percent ',
                ' equals ',
            ),
            $string
        );
    }
}