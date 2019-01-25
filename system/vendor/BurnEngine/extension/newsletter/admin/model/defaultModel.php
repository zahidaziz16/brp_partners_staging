<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class Newsletter_Admin_DefaultModel extends Newsletter_DefaultModel
{
    public function install()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'burnengine_newsletter` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `email` varchar(255) NOT NULL,
                  `name` varchar(255) NOT NULL,
                  `created_at` datetime NOT NULL,
                  PRIMARY KEY (`id`),
                  INDEX (`email`)
               ) ENGINE=MyISAM COLLATE=utf8_general_ci;';
        $this->db->query($sql);

        $this->getSettingsModel()->setAndPersistScopeSettings('settings', array(
            'show_name'           => 0,
            'subscribe_customers' => 0
        ));
    }

    public function uninstall()
    {
        $this->dbHelper->dropTable('burnengine_newsletter');
        $this->getSettingsModel()->deleteScopeSettings('settings');
    }

    public function saveSettings(array $settings)
    {
        $this->getSettingsModel()->setAndPersistScopeSettings('settings', $settings);
    }

    public function getSubscribers($data = array(), $total = false)
    {
        if (!$total) {
            $sql = 'SELECT *';
        } else {
            $sql = 'SELECT COUNT(bn.id) AS total';
        }

        $sql .= ' FROM ' . DB_PREFIX . 'burnengine_newsletter AS bn';

        if (!empty($data['search_string'])) {
            $str = $this->dbHelper->quote($data['search_string']);
            $sql .= ' AND (bn.email LIKE %' . $str . '% OR bn.name LIKE %' . $str . '%)';
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

    public function deleteSubscribers(array $ids)
    {
        $ids = array_map('intval', $ids);
        $sql = 'DELETE FROM ' . DB_PREFIX . 'burnengine_newsletter WHERE id IN(' . implode(',', $ids) . ')';

        $this->db->query($sql);
    }
}