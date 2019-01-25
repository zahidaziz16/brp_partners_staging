<?php
class TB_DbHelper
{
    /**
     * @var DB
     */
	protected $db;

    /**
     * @var string
     */
    protected $db_prefix;

	public function __construct($db, $db_prefix = '')
    {
		$this->db = $db;
        $this->db_prefix = $db_prefix;
	}

    /**
     * @param string $table_name
     * @param array $data
     * @return string
     */
    public function addRecord($table_name, array $data)
    {
        $table_name = $this->db_prefix . $table_name;

        $fields = array_keys($data);
        $values = array_values($data);

        foreach ($fields as $key => $field) {
            $fields[$key] = '`' . $field . '`';
            $values[$key] = $this->quote($values[$key]);
        }

        $fieldList = implode(', ', $fields);
        $valueList = implode(', ', $values);

        $sql = "INSERT INTO `$table_name` ($fieldList) VALUES ($valueList)";

        return $this->db->query($sql);
    }

    public function addMultipleRecords($table_name, array $fields, array $data)
    {
        if (empty($data)) {
            return 0;
        }

        $table_name = $this->db_prefix . $table_name;
        $fields = '`' . implode('`,`', $fields) . '`';

        $sql_values = array();
        foreach($data as $row) {
            foreach ($row as &$row_value) {
                $row_value = $this->quote($row_value);
            }
            $sql_values[] = '(' . implode(',', $row) . ')';
        }
        $sql_values = implode(',', $sql_values);

        $sql = "INSERT INTO `$table_name` ($fields) VALUES $sql_values";

        return $this->db->query($sql);
    }

    /**
     * @param string $table_name
     * @param array $data
     * @param null|string $where
     * @return string
     */
    public function updateRecord($table_name, array $data, $where = null)
    {
        $table_name = $this->db_prefix . $table_name;

        $fields = array_keys($data);
        $values = array_values($data);
        $tmp    = array();

        foreach ($fields as $key => $field) {
            $tmp[] = '`' . $field . '` = ' . $this->quote($values[$key]);
        }
        $fieldList = implode(', ', $tmp);

        if (is_array($where)) {
            $where = $this->parseWhere($where);
        }

        $sql = "UPDATE `$table_name` SET $fieldList";
        if (!empty($where)) {
            $sql .= ' WHERE ' . $where;
        }

        return $this->db->query($sql);
    }

    /**
     * @param string $table_name
     * @param string|array $where
     * @return string
     */
    public function deleteRecord($table_name, $where)
    {
        if (empty($where)) {
            return false;
        }

        $table_name = $this->db_prefix . $table_name;

        if (is_array($where)) {
            $where = $this->parseWhere($where);
        }

        $sql = "DELETE FROM `$table_name` WHERE $where";

        return $this->db->query($sql);
    }

    /**
     * @param string $table_name
     * @param array|string $where
     * @param array $options
     * @return array|false
     */
    public function getRecords($table_name, $where = '', $options = array())
    {
        $table_name = $this->db_prefix . $table_name;

        if (is_array($where)) {
            $where = $this->parseWhere($where);
        }

        if (empty($where)) {
            $where = '1';
        }

        $fields = !empty($options['fields']) ? $options['fields'] : '*';

        $sql = "SELECT $fields
                FROM `$table_name`
                WHERE $where";
        if (!$result = $this->db->query($sql)->rows) {
            return array();
        }

        if (!empty($options['column'])) {
            $index_key = null;
            if (is_string($options['column'])) {
                $index_key = $options['column'];
            }

            return array_column($result, key($result[0]), $index_key);
        }

        if (!empty($options['pairs'])) {
            $field1 = key($result[0]);
            next($result[0]);
            $field2 = key($result[0]);
            return array_column($result, $field2, $field1);
        }

        $index_field = !empty($options['index_field']) ? $options['index_field'] : false;

        if ($index_field) {
            $indexed_result = array();
            foreach ($result as $record) {
                $indexed_result[$record[$index_field]] = $record;
            }

            return $indexed_result;
        }

        $group_field = !empty($options['group_field']) ? $options['group_field'] : false;

        if ($group_field) {
            $grouped_result = array();
            if (is_array($group_field)) {
                $value_field = current($group_field);
                $group_field = key($group_field);
            }
            foreach ($result as $record) {
                $grouped_result[$record[$group_field]][] = isset($value_field) ? $record[$value_field] : $record;
            }

            return $grouped_result;
        }

        return !empty($result) ? $result : array();
    }

    public function getRecord($table_name, $where)
    {
        if (empty($where)) {
            return false;
        }

        $records = $this->getRecords($table_name, $where);

        if ($records) {
            return $records[0];
        }

        return false;
    }

    public function getValue($table_name, $field, $where)
    {
        $record = $this->getRecord($table_name, $where);

        if (!isset($record[$field])) {
            return false;
        }

        return $record[$field];
    }

    public function getCount($table_name, $where = '')
    {
        $table_name = $this->db_prefix . $table_name;

        $sql = 'SELECT COUNT(*) AS cnt FROM `' . $table_name . '`';

        if (is_array($where)) {
            $where = $this->parseWhere($where);
        }

        if (!empty($where)) {
            $sql .= ' ' . $where;
        }

        if ($result = $this->db->query($sql)->row) {
            return (int) $result['cnt'];
        }

        return 0;
    }

    public function getColumn($table_name, $field, $where = '')
    {
        return $this->getRecords($table_name, $where, array('fields' => $field, 'column' => true));
    }

    public function getPairs($table_name, $field1, $field2, $where = '')
    {
        return $this->getRecords($table_name, $where, array('fields' => $field1 . ',' . $field2, 'pairs' => true));
    }

    public function getGroup($table_name, $group_field, $where = '')
    {
        return $this->getRecords($table_name, $where, array('group_field' => $group_field));
    }

    public function importSQL($sql_lines)
    {
        $sql_statement = '';

        foreach ((array) $sql_lines as $line) {

            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }

            $sql_statement .= $line;

            if (substr(trim($line), -1, 1) == ';') {
                $this->db->query($sql_statement);
                $sql_statement = '';
            }
        }
    }

    public function tableExists($table_name)
    {
        return $this->db->query('SHOW TABLES LIKE ' . $this->quote($this->db_prefix . $table_name))->num_rows;
    }

    public function columnExists($table_name, $column)
    {
        return $this->db->query('SHOW COLUMNS FROM `' . $this->db_prefix . $table_name . '` LIKE ' . $this->quote($column))->num_rows;
    }

    public function addColumn($table, $column, $type)
    {
        if (!$this->columnExists($table, $column)) {
            $this->db->query( 'ALTER TABLE ' . $this->db_prefix . $table . ' ADD `' . $column . '` ' . $type );
        }
    }

    public function dropColumn($table, $column)
    {
        if ($this->columnExists($table, $column)) {
            $this->db->query('ALTER TABLE ' . $this->db_prefix . $table . ' DROP `' . $column . '`');
        }
    }

    public function truncateTable($table, $safe = false)
    {
        foreach ((array) $table as $table_name) {
            if ($safe && !$this->tableExists($table_name)) {
                continue;
            }

            $this->db->query('TRUNCATE TABLE `' . $this->db_prefix . $table_name . '`');
        }
    }

    public function dropTable($table)
    {
        foreach ((array) $table as $table_name) {
            $this->db->query('DROP TABLE IF EXISTS ' . $this->db_prefix . $table_name);
        }
    }

    public function getFoundRows()
    {
        return (int) $this->db->query('SELECT FOUND_ROWS() AS cnt')->row['cnt'];
    }

    public function getLastId()
    {
        return $this->db->getLastId();
    }

    public function countAffected()
    {
        return $this->db->countAffected();
    }

    public function quote($string)
    {
        return "'" . $this->db->escape($string) . "'";
    }

    public function getDb()
    {
        return $this->db;
    }

    public function getDbPrefix()
    {
        return $this->db_prefix;
    }

    protected function parseWhere(array $where)
    {
        $fields = array_keys($where);
        $values = array_values($where);
        $tmp    = array();

        foreach ($fields as $key => $field) {
            if (!is_array($values[$key])) {
                $tmp[] = '`' . $field . '` = ' . $this->quote($values[$key]);
            } else {
                $set = array();
                foreach ($values[$key] as $set_value) {
                    $set[] = $this->quote($set_value);
                }
                $tmp[] = '`' . $field . '` IN (' . implode(',', $set) . ')';
            }
        }

        return implode(' AND ', $tmp);
    }
}