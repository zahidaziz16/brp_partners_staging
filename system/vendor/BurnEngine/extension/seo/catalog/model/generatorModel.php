<?php

require_once dirname(__FILE__) . '/../../model/generatorModel.php';

class Seo_Catalog_GeneratorModel extends Seo_GeneratorModel
{
	public function buildH1Heading($item_type, $item_id, $settings)
	{
		$record = $this->getRecord($item_type, $item_id, $this->context->getCurrentLanguage('id'));

		return $this->{'extract' . ucfirst($item_type) . 'Text'}($record, $settings['pattern']);
	}

	protected function extractProductText($product, $main_pattern)
	{
		/** @var Theme_Catalog_ProductsModel $productsModel */
		$productsModel = $this->getModel('products');

		$category_name = '';
		$categories = $productsModel->getProductCategories($product['product_id']);
		if (!empty($categories)) {
			$category = reset($categories);
			$category_name = $category['name'];
		}


		$manufacturer_name = '';
		$manufacturer = $this->dbHelper->getRecord('manufacturer', array('manufacturer_id' => $product['manufacturer_id']));
		if (!empty($manufacturer)) {
			$manufacturer_name = $manufacturer['name'];
		}

		return trim(str_replace(
			array_merge(array('[name]', '[model]', '[sku]', '[upc]', '[manufacturer]', '[category]')),
			array_merge(array(
				$product['name'],
				$product['model'],
				$product['sku'],
				$product['upc'],
				$manufacturer_name,
				$category_name
			)),
			$main_pattern
		));
	}

	protected function extractCategoryText($category, $main_pattern)
	{
		$parent_name = '';
		if ($category['parent_id']) {
			/** @var Theme_Catalog_CategoryModel $categoryModel */
			$categoryModel = $this->getModel('category');

			$category_parent = $categoryModel->getCategory($category['parent_id']);
			$parent_name = $category_parent['name'];
		}

		return trim(str_replace(
			array_merge(array('[name]', '[parent_name]')),
			array_merge(array(
				$category['name'],
				$parent_name
			)),
			$main_pattern
		));
	}

	protected function getRecord($item_type, $item_id, $language_id)
	{
		$table = $this->getTableData($item_type);

		$sql = 'SELECT
                    main_table.*' . ($table['main_table'] != $table['lang_table'] ? ',lang_table.*' : '') . ',
                    main_table.' . $table['primary_key'] . ' AS id,
                    ' . $table['lang_field'] . ' AS lang_field, language_id';

		$sql .= ' FROM ' . DB_PREFIX . $table['main_table'] . ' AS main_table';

		if ($table['main_table'] != $table['lang_table']) {
			$sql .= ' LEFT JOIN ' . DB_PREFIX . $table['lang_table'] . ' AS lang_table ON main_table.' . $table['primary_key'] . ' = lang_table.' . $table['primary_key'];
		}

		$sql .= ' WHERE main_table.' . $table['primary_key'] . ' = ' . $item_id . ' AND language_id = ' . $language_id;

		if (!empty($table['sql_cond'])) {
			$sql .= ' AND ' . $table['sql_cond'];
		}

		return $this->db->query($sql)->row;
	}
}