<?php

require_once DIR_SYSTEM . 'vendor/stories/model.php';

class ModelStoriesTag extends ModelStoriesModel
{
    public function insert($data)
    {
        $this->dbHelper->insert('story_tag', array(
            'language_id'      => (int) $data['language_id'],
            'name'             => (string) $data['name'],
            'description'      => (string) $data['description'],
            'meta_title'       => (string) $data['meta_title'],
            'meta_description' => (string) $data['meta_description'],
            'status'           => (int) $data['status'],
            'date_added'       => date('Y-m-d', time())
        ));

        $tag_id = $this->dbHelper->getLastId();
        $this->insertKeyword($tag_id, $data['keyword']);

        return $tag_id;
    }

    public function update($tag_id, $data)
    {
        settype($tag_id, 'integer');

        if (empty($tag_id)) {
            return;
        }

        $this->dbHelper->update('story_tag',
            array(
                'name'             => (string) $data['name'],
                'description'      => (string) $data['description'],
                'meta_title'       => (string) $data['meta_title'],
                'meta_description' => (string) $data['meta_description'],
                'status'           => (int) $data['status'],
                'date_updated'     => date('Y-m-d', time())
            ),
            array('tag_id' => $tag_id)
        );

        $this->insertKeyword($tag_id, $data['keyword']);
    }

    protected function insertKeyword($tag_id, $keyword)
    {
        $this->dbHelper->delete('url_alias', array('query' => 'story_tag_id=' . $tag_id));

        $keyword = trim((string) $keyword);
        if (strlen($keyword)) {
            $this->dbHelper->insert('url_alias', array(
                'query'   => 'story_tag_id=' . $tag_id,
                'keyword' => $keyword
            ));
        }
    }

    public function getOne($id)
    {
        if ($tag = $this->dbHelper->getRecord('story_tag', array('tag_id' => (int) $id))) {

            $keyword = $this->dbHelper->getValue('url_alias', 'keyword', array('query' => 'story_tag_id=' . (int) $id));
            $tag['keyword'] = $keyword ? $keyword : '';

            return $tag;
        }

        return false;
    }

    public function getAll(array $options = array())
    {
        $sql = 'SELECT st.*, l.name AS language_name
                FROM ' . DB_PREFIX . 'story_tag AS st
                LEFT JOIN ' . DB_PREFIX . 'language AS l ON st.language_id = l.language_id
                WHERE 1';

        if (isset($options['status'])) {
            $sql .= ' AND st.status = ' . (int) $options['status'];
        }

        if (isset($options['language_id'])) {
            $sql .= ' AND st.language_id = ' . (int) $options['language_id'];
        }

        if (!isset($options['sort'])) {
            $options['sort'] = 'st.date_added';
        }

        $sql .= ' ORDER BY ' . $options['sort'] . ', LCASE(st.name)';

        if (isset($options['order']) && ($options['order'] == 'DESC')) {
            $sql .= ' DESC';
        } else {
            $sql .= ' ASC';
        }

        if (isset($options['start']) || isset($options['limit'])) {
            if ($options['start'] < 0) {
                $options['start'] = 0;
            }
            if ($options['limit'] < 1) {
                $options['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $options['start'] . "," . (int) $options['limit'];
        }

        return $this->db->query($sql)->rows;
    }

    public function deleteOne($id)
    {
        $this->dbHelper->delete('story_tag', array('tag_id' => $id));
        $this->dbHelper->delete('story_to_tag', array('tag_id' => $id));
        $this->dbHelper->delete('url_alias', array('query' => 'story_tag_id=' . (int) $id));
    }

    public function getCount()
    {
        return $this->dbHelper->getCount('story_tag');
    }
}
