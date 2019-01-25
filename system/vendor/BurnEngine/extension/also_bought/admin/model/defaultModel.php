<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class AlsoBought_Admin_DefaultModel extends AlsoBought_DefaultModel
{
    public function install()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'burnengine_also_bought` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `pid1` INT(11) NOT NULL,
                  `pid2` INT(11) NOT NULL,
                  `matches` INT(11) NOT NULL DEFAULT \'0\',
                  PRIMARY KEY (`id`),
                  INDEX product_ids (pid1,pid2)
               ) ENGINE=MyISAM COLLATE=utf8_general_ci;';
        $this->db->query($sql);

        $this->installMod();
    }

    public function uninstall()
    {
        $this->dbHelper->dropTable('burnengine_also_bought');
        $this->removeMod();
    }

    protected function installMod()
    {
        $this->removeMod();

        if (!$this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->installVQmod($this->extension->getRootDir() . '/config/data/vqmod/also_bought.xml');
        } else {
            $contents = file_get_contents($this->extension->getRootDir() . '/config/data/ocmod/also_bought.ocmod.xml');
            $this->engine->getThemeExtension()->getInstaller()->installOcMod($contents, true);
        }
    }

    protected function removeMod()
    {
        if (!$this->engine->gteOc2()) {
            $this->engine->getThemeExtension()->getInstaller()->removeVQmod('also_bought.xml');
        } else {
            $this->engine->getThemeExtension()->getInstaller()->removeOcMod('tbExtensionAlsoBought', true);
        }
    }

    public function getAlsoBoughtProductIds($product_ids, $data)
    {
        $sql = 'SELECT op.product_id
        ';

        if  (isset($data['sort']) && $data['sort'] == 'rating') {
            $sql .= ',
                        (SELECT AVG(rating) AS total
                         FROM ' . DB_PREFIX . 'review r1
                         WHERE r1.product_id = p.product_id
                            AND r1.status = 1
                            GROUP BY r1.product_id
                        ) AS rating
                    ';
        }

        $sql .= 'FROM ' . DB_PREFIX . 'order_product op
                INNER JOIN `' . DB_PREFIX . 'product` p ON (op.product_id = p.product_id)
                INNER JOIN ' . DB_PREFIX . 'product_description AS pd ON p.product_id = pd.product_id
                INNER JOIN ' . DB_PREFIX . 'product_to_store p2s ON (p.product_id = p2s.product_id)
                WHERE EXISTS (
                    SELECT 1
                    FROM ' . DB_PREFIX . 'order_product op1
                    WHERE op1.order_id = op.order_id AND op1.product_id IN (' . implode(',', $product_ids) . ')
                )
                AND op.product_id NOT IN (' . implode(',', $product_ids) . ')
                AND p.status = 1
                AND p.date_available <= NOW()
                AND p2s.store_id = ' . (int) $this->getOcConfig()->get('config_store_id') . '
                AND pd.language_id = ' . (int) $this->getOcConfig()->get('config_language_id') . '
                GROUP BY op.product_id';

        if ($data['randomize']) {
            $sql .= ' ORDER BY RAND()';
        } else
            if  (isset($data['sort']) && $data['sort'] == 'rating' && $data['order'] == 'ASC') {
                $sql .= ' ORDER BY -rating DESC';
            } else {
                $sql .= " ORDER BY " . (isset($data['sort']) ? $data['sort'] : 'pd.name');
                $sql .= ' ' . (isset($data['order']) ? $data['order'] : 'ASC');
            }

        if (isset($data['start']) || isset($data['limit'])) {
            $sql .= " LIMIT " . abs((int) $data['start']) . "," . abs((int) $data['limit']);
        }

        return array_column($this->db->query($sql)->rows, 'product_id');
    }
}