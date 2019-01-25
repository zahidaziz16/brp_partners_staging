<?php

class Seo_GeneratorModel extends TB_ExtensionModel
{
	protected function getTableData($key = null)
	{
		$table_data = array(
			'product' => array(
				'primary_key'     => 'product_id',
				'main_table'      => 'product',
				'lang_table'      => 'product_description',
				'lang_field'      => 'name',
				'lang_field_prop' => 'product_description.{language_id}.name',
			),
			'category' => array(
				'primary_key'     => 'category_id',
				'main_table'      => 'category',
				'lang_table'      => 'category_description',
				'lang_field'      => 'name',
				'lang_field_prop' => 'category_description.{language_id}.name',
				'sql_cond'        => 'main_table.status = 1'
			),
			'manufacturer' => array(
				'primary_key'     => 'manufacturer_id',
				'main_table'      => 'manufacturer',
				'lang_table'      => 'manufacturer',
				'lang_field'      => 'name',
				'lang_field_prop' => '',
			),
			'information' => array(
				'primary_key'     => 'information_id',
				'main_table'      => 'information',
				'lang_table'      => 'information_description',
				'lang_field'      => 'title',
				'lang_field_prop' => 'information_description.{language_id}.title',
			),
			'story' => array(
				'primary_key'     => 'story_id',
				'main_table'      => 'story',
				'lang_table'      => 'story_description',
				'lang_field'      => 'title',
				'lang_field_prop' => 'lang.{language_id}.title',
			)
		);

		if (null !== $key) {
			return $table_data[$key];
		}

		return $table_data;
	}
}