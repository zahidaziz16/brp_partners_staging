<?php

class TB_DbSettingsHelper
{
    /**
     * @var TB_DbHelper
     */
    protected $dbHelper;

    /**
     * @var string
     */
    protected $settings_table;

    /**
     * @var DB
     */
    protected $db;

    protected $group_field_name = 'group';

    protected $use_serialize;

    public function __construct(TB_DbHelper $dbHelper, $settings_table = 'burnengine_setting', $use_serialize = true)
    {
        $this->dbHelper = $dbHelper;
        $this->settings_table = $settings_table;
        $this->use_serialize = $use_serialize;
        $this->db = $dbHelper->getDb();
    }

    public function setGroupFieldName($name)
    {
        $this->group_field_name = $name;
    }

    /**
     * @param string $key
     * @param int $store_id
     * @param string $group
     *
     * @return null|mixed
     */
    public function getKey($key, $store_id, $group = '')
    {
        $sql = 'SELECT `value`, `serialized` FROM ' .
                $this->dbHelper->getDbPrefix() . $this->settings_table . '
                WHERE `store_id` = ' . (int) $store_id . ' AND
                      `key` = '      . $this->dbHelper->quote($key);
        if (!empty($group)) {
            $sql .= ' AND `' . $this->group_field_name . '` = '    . $this->dbHelper->quote($group);
        }

        $result = $this->db->query($sql);

        if (!$result->num_rows) {
            return null;
        }

        return $result->row['serialized'] == '1' ? $this->decodeValue($result->row['value']) : $result->row['value'];
    }

    public function keyExists($key, $store_id, $group = '')
    {
        $sql = 'SELECT 1 FROM ' .
            $this->dbHelper->getDbPrefix() . $this->settings_table . '
                WHERE `store_id` = ' . (int) $store_id . ' AND
                      `key` = '      . $this->dbHelper->quote($key);
        if (!empty($group)) {
            $sql .= ' AND `' . $this->group_field_name . '` = '    . $this->dbHelper->quote($group);
        }

        return $this->db->query($sql)->num_rows > 0;
    }

    public function groupExists($group, $store_id)
    {
        $sql = 'SELECT 1 FROM ' .
            $this->dbHelper->getDbPrefix() . $this->settings_table . '
                WHERE `store_id` = ' . (int) $store_id . ' AND
                      `' . $this->group_field_name . '` = '    . $this->dbHelper->quote($group);

        return $this->db->query($sql)->num_rows > 0;
    }

    public function deleteKey($key, $store_id, $group = '')
    {
        $where = array(
            'store_id' => $store_id,
            'key'      => $key
        );
        if (!empty($group)) {
            $where[$this->group_field_name] = $group;
        }

        $this->dbHelper->deleteRecord($this->settings_table, $where);
    }

    public function deleteKeyBeginsWith($begin_with, $store_id, $group = '')
    {
        $where = '`store_id` = ' . $store_id . ' AND `key` LIKE "' . $begin_with . '%"';

        if (!empty($group)) {
            $where .= ' AND `' . $this->group_field_name . '` = ' . $this->dbHelper->quote($group);
        }

        $this->dbHelper->deleteRecord($this->settings_table, $where);
    }

    public function persistKey($key, $store_id, $group, $value)
    {
        if ($this->keyExists($key, $store_id, $group)) {
            $where = array(
                'store_id'              => $store_id,
                $this->group_field_name => $group,
                'key'                   => $key
            );
            $this->dbHelper->updateRecord($this->settings_table, array(
                'value'      => $this->encodeValue($value),
                'serialized' => is_array($value) ? 1 : 0
            ), $where);
        } else {
            $this->dbHelper->addRecord($this->settings_table, array(
                'store_id'              => $store_id,
                $this->group_field_name => $group,
                'key'                   => $key,
                'value'                 => $this->encodeValue($value),
                'serialized'            => is_array($value) ? 1 : 0
            ));
        }
    }

    public function getKeys($store_id, $group = '')
    {
        $sql = 'SELECT setting_id, `key` FROM ' .
            $this->dbHelper->getDbPrefix() . $this->settings_table . '
                WHERE `store_id` = ' . (int) $store_id;
        if (!empty($group)) {
            $sql .= ' AND `' . $this->group_field_name . '` = ' . $this->dbHelper->quote($group);
        }

        return array_column($this->db->query($sql)->rows, 'key', 'setting_id');
    }

    public function getKeyCollection($keys, $store_id, $group = '')
    {
        $sql = 'SELECT *
                FROM ' . $this->dbHelper->getDbPrefix() . $this->settings_table . '
                WHERE `store_id` = ' . $store_id . ' AND
                      `key` IN (\'' . implode("','", $keys) . '\')';

        if (!empty($group)) {
            $sql .= ' AND `' . $this->group_field_name . '` = ' . $this->dbHelper->quote($group);
        }

        $result = array();
        foreach ($this->db->query($sql)->rows as $row) {
            if ($row['serialized'] == '1') {
                $result[$row['key']] = $this->decodeValue($row['value']);
            } else {
                $result[$row['key']] = $row['value'];
            }
        }

        return $result;
    }

    public function getKeysGroupCollection($keys, $store_id)
    {
        $sql = 'SELECT `group`, `key`, `value`
                FROM ' . $this->dbHelper->getDbPrefix() . $this->settings_table . '
                WHERE `store_id` = ' . $store_id;

        $sql_keys = array();
        foreach ($keys as $group => $db_keys) {
            $sql_keys[] = '(`' . $this->group_field_name . '` = ' . $this->dbHelper->quote($group) . ' AND `key` IN (\'' . implode("','", $db_keys) . '\'))';
        }

        $sql .= ' AND (' . implode(' OR ', $sql_keys) . ')';

        $result = array();
        foreach ($this->db->query($sql)->rows as $row) {
            $result[$row['group'] . '_' . $row['key']] = unserialize($row['value']);
        }

        return $result;
    }

    public function getKeysBeginWith($begin_with, $store_id, $group)
    {
        $sql = 'SELECT *
                FROM ' . $this->dbHelper->getDbPrefix() . $this->settings_table . '
                WHERE `store_id` = ' . $store_id . ' AND
                      `' . $this->group_field_name . '` = ' . $this->dbHelper->quote($group) . ' AND
                      `key` LIKE "' . implode('%" OR `key` LIKE "', (array) $begin_with) . '%"';

        $result = array();
        foreach ($this->db->query($sql)->rows as $row) {
            if ($row['serialized'] == '1') {
                $result[$row['key']] = $this->decodeValue($row['value']);
            } else {
                $result[$row['key']] = $row['value'];
            }
        }

        return $result;
    }

    public function getGroup($group, $store_id)
    {
        $sql = 'SELECT `key`, `value`, `serialized`
                FROM ' . $this->dbHelper->getDbPrefix() . $this->settings_table . '
                WHERE `store_id` = ' . (int) $store_id . ' AND
                      `' . $this->group_field_name . '` = ' . $this->dbHelper->quote($group);

        $result = array();
        foreach ($this->db->query($sql)->rows as $row) {
            if ($row['serialized'] == '1') {
                $result[$row['key']] = $this->decodeValue($row['value']);
            } else {
                $result[$row['key']] = $row['value'];
            }
        }

        return $result;
    }

    public function getGroupKeys($group, $store_id)
    {
        $where = '`store_id` = ' . (int) $store_id;

        settype($group, 'array');
        foreach ($group as &$group_name) {
            $group_name = $this->dbHelper->quote($group_name);
        }
        $where .= ' AND (`' . $this->group_field_name . '` = ' . implode(' OR `' . $this->group_field_name . '` = ', $group) . ')';

        return $this->dbHelper->getGroup($this->settings_table, array('group' => 'key'), $where);
    }

    public function persistGroup($group, $data, $store_id)
    {
        foreach ($data as $key => $value) {
            $this->persistKey($key, $store_id, $group, $value);
        }
    }

    public function deleteGroup($group, $store_id)
    {
        $this->dbHelper->deleteRecord($this->settings_table, array(
            $this->group_field_name => $group,
            'store_id'              => $store_id
        ));
    }

    protected function encodeValue($value)
    {
        if (is_scalar($value)) {
            return($value);
        }

        return $this->use_serialize ? serialize($value) : json_encode($value);
    }

    protected function decodeValue($value)
    {
        if ($this->use_serialize) {
            if (!is_string($value) || empty($value)) {
                return array();
            }

            $old_err = error_reporting();
            error_reporting(0);

            $result = unserialize($value);

            error_reporting($old_err);

            if (false === $result) {
                return array();
            }

            return $result;
        }

        return json_decode($value, true);
    }
}