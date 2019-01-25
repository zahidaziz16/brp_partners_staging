<?php

require_once dirname(__FILE__) . '/../../model/defaultModel.php';

class AlsoBought_Catalog_DefaultModel extends AlsoBought_DefaultModel
{
    protected $table = 'burnengine_also_bought';

    public function updateBoughtCombinations($products_ids)
    {
        sort($products_ids);

        $cnt = count($products_ids);
        $combination = array();

        foreach ($products_ids as $index => $id) {
            for ($i = 1; $i < $cnt; $i++) {
                if (!isset($products_ids[$index+$i])) {
                    continue;
                }
                $combination[] = array((int) $id, (int) $products_ids[$index+$i]);
            }
        }

        foreach($combination as $pair) {
            $record = $this->dbHelper->getRecord($this->table, array('pid1' => $pair[0], 'pid2' => $pair[1]));

            if (!$record) {
                $this->dbHelper->addRecord($this->table, array(
                    'pid1'       => $pair[0],
                    'pid2'       => $pair[1],
                    'matches'    => 1
                ));
            } else {
                $this->dbHelper->updateRecord($this->table, array('matches' => (int) $record['matches'] + 1), array('id' => $record['id']));
            }
        }
    }

    public function getAlsoBoughtProductsIds($product_ids, $options)
    {
        $product_ids = (array) $product_ids;

        if (count($product_ids) == 1) {
            $pid = reset($product_ids);
            $rows = $this->dbHelper->getRecords($this->table, "pid1 = $pid OR pid2 = $pid", array(
                'order' => 'matches DESC',
                'limit' => $options['limit']
            ));
        } else {
            $rows = array();
        }

        $result = array();

        foreach ($rows as $row) {
            $key = in_array($row['pid1'], $product_ids) ? 'pid2' : 'pid1';
            $result[] = $row[$key];
        }

        return $result;
    }
}