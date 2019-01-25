<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class ProductFields_Catalog_DefaultModel extends ProductFields_DefaultModel
{
    public function getProductFieldContent($product_id, $field_id)
    {
        $field = $this->getField($field_id);

        if (!$field) {
            return '';
        }

        $content = $this->dbHelper->getValue('burnengine_product_field_content', 'content',
            array(
                'product_field_id' => $field['id'],
                'product_id'       => $product_id,
                'language_id'      => $this->context->getCurrentLanguage('language_id')
            )
        );

        if (!empty($content)) {
            return html_entity_decode($content, ENT_COMPAT, 'UTF-8');
        }

        if (empty($field['defaults'])) {
            return '';
        }

        $language_code = $this->context->getCurrentLanguage('code');

        /** @var Theme_Catalog_ProductsModel $productModel */
        $productModel = $this->engine->getThemeExtension()->getModel('products');

        foreach ($field['defaults'] as $default) {
            if (!isset($default['lang'][$language_code]['content'])) {
                continue;
            }

            if ($default['available_for'] == 'all') {
                $content = html_entity_decode($default['lang'][$language_code]['content'], ENT_COMPAT, 'UTF-8');
            } else {
                $product_categories = array_column($productModel->getProductCategories($product_id), 'category_id');
                if (count(array_intersect($default['categories'], $product_categories))) {
                    $content = html_entity_decode($default['lang'][$language_code]['content'], ENT_COMPAT, 'UTF-8');
                }
            }
        }

        $content = trim($content);

        if ($content == '<p><br></p>') {
            $content = '';
        }

        return $content;
    }

    protected function getField($field_id)
    {
        $fields = $this->getActiveFields();

        if (!isset($fields[$field_id])) {
            return null;
        }

        return $fields[$field_id];
    }

    protected function getActiveFields()
    {
        static $fields;

        if (null !== $fields) {
            return $fields;
        }

        $fields = array();

        foreach ($this->dbHelper->getRecords('burnengine_product_field', array('is_active' => 1)) as $field) {
            if (!empty($field['defaults'])) {
                $field['defaults'] = json_decode($field['defaults'], true);
            }

            $fields[$field['id']] = $field;
        }

        return $fields;
    }
}