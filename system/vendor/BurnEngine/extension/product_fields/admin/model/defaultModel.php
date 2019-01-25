<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class ProductFields_Admin_DefaultModel extends ProductFields_DefaultModel
{
    public function checkRequirements()
    {
        if ($this->engine->gteOc2()) {
            return true;
        }
        
        return $this->engine->getThemeExtension()->getModel('extensions')->requireVQMod();
    }

    public function install()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'burnengine_product_field` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `is_active` tinyint NOT NULL DEFAULT 0,
                  `block_name` varchar(255) NOT NULL,
                  `defaults` LONGTEXT COLLATE utf8_unicode_ci NOT NULL,
                  `created_at` datetime NOT NULL,
                  PRIMARY KEY (`id`)
               ) ENGINE=MyISAM COLLATE=utf8_general_ci;';
        $this->db->query($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'burnengine_product_field_content` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `product_field_id` INT(11) NOT NULL,
                  `product_id` INT(11) NOT NULL,
                  `language_id` INT(11) NOT NULL,
                  `content` LONGTEXT COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`),
                  INDEX (`product_field_id`),
                  INDEX (`product_id`, `language_id`)
               ) ENGINE=MyISAM COLLATE=utf8_general_ci;';
        $this->db->query($sql);

        if ($this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->installOcMod($this->extension->getRootDir() . '/config/data/ocmod/product_fields.ocmod.xml');
        } else {
            $this->engine->getThemeExtension()->getInstaller()->installVQmod($this->extension->getRootDir() . '/config/data/vqmod/product_fields.xml');
        }
    }

    public function uninstall()
    {
        $this->dbHelper->dropTable('burnengine_product_field');
        $this->dbHelper->dropTable('burnengine_product_field_content');

        if ($this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->removeOcMod('tbProductFields', true);
        } else {
            $this->engine->getThemeExtension()->getInstaller()->removeVQmod('product_fields.xml');
        }
    }

    public function upgrade()
    {
        if ($this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->removeOcMod('tbProductFields');
            $this->engine->getThemeExtension()->getInstaller()->installOcMod($this->extension->getRootDir() . '/config/data/ocmod/product_fields.ocmod.xml');
        } else {
            $this->engine->getThemeExtension()->getInstaller()->removeVQmod('product_fields.xml');
            $this->engine->getThemeExtension()->getInstaller()->installVQmod($this->extension->getRootDir() . '/config/data/vqmod/product_fields.xml');
        }
    }

    public function initField(array &$field)
    {
        $default_settings = array(
            'id'         => '',
            'is_active'  => 1,
            'block_name' => TB_Utils::randName(7),
            'defaults'   => array()
        );

        $field = TB_FormHelper::initFlatVarsSimple($default_settings, $field);
    }

    public function saveField($data)
    {
        $insert_data = array(
            'is_active'  => (int) $data['is_active'],
            'block_name' => (string) $data['block_name'],
            'defaults'   => isset($data['defaults']) ? json_encode((array) $data['defaults']) : ''
        );

        if (!empty($data['id'])) {
            $this->dbHelper->updateRecord('burnengine_product_field', $insert_data, array('id' => $data['id']));
        } else {
            $insert_data['created_at'] = date("Y-m-d H:i:s");
            $this->dbHelper->addRecord('burnengine_product_field', $insert_data);
        }
    }

    public function getFields(array $where = array())
    {
        return $this->dbHelper->getRecords('burnengine_product_field', $where);
    }

    public function getProductFields($product_id = null)
    {
        $fields = $this->getFields(array('is_active' => 1));

        foreach ($fields as &$field) {
            $content = array();
            if (null !== $product_id) {
                $content = $this->dbHelper->getRecords('burnengine_product_field_content',
                    array('product_field_id' => $field['id'], 'product_id' => $product_id),
                    array('fields' => 'content, language_id', 'column' => 'language_id'));
            }

            foreach ($this->engine->getEnabledLanguages() as $language) {
                $field['content'][$language['id']] = isset($content[$language['id']]) ? $content[$language['id']] : '';
            }
        }

        return $fields;
    }

    public function addProductFields($product_id, $fields)
    {
        foreach ($fields as $field_id => $field) {
            foreach ($field['content'] as $language_id => $content) {
                $where = array(
                    'product_field_id' => $field_id,
                    'product_id'       => $product_id,
                    'language_id'      => $language_id
                );

                $insert_data = array(
                    'content' => $content
                );

                $current_record = $this->dbHelper->getRecord('burnengine_product_field_content', $where);

                if (false !== $current_record) {
                    $this->dbHelper->updateRecord('burnengine_product_field_content', $insert_data, $where);
                } else {
                    $this->dbHelper->addRecord('burnengine_product_field_content', array_merge($where, $insert_data));
                }
            }
        }
    }

    public function getField($id)
    {
        $field = $this->dbHelper->getRecord('burnengine_product_field', array('id' => (int) $id));

        if (!empty($field['defaults'])) {
            $field['defaults'] = json_decode($field['defaults'], true);
        } else {
            $field['defaults'] = array();
        }

        return $field;
    }

    public function deleteField($id)
    {
        $this->dbHelper->deleteRecord('burnengine_product_field', array('id' => $id));
        $this->dbHelper->deleteRecord('burnengine_product_field_content', array('product_field_id' => $id));
    }
}