<?php

require_once tb_modification(DIR_SYSTEM . 'vendor/stories/model.php');

class ModelStoriesIndex extends ModelStoriesModel
{
    protected $fetched_stories = array();

    public function getStories(array $options = array())
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS s.*, sd.*, GROUP_CONCAT(st.name, "<=>", st.tag_id SEPARATOR "!*!") AS tags
                FROM ' . DB_PREFIX . 'story AS s
                INNER JOIN ' . DB_PREFIX . 'story_description AS sd ON s.story_id = sd.story_id AND sd.language_id = ' . (int) $this->config->get('config_language_id') . '
                INNER JOIN ' . DB_PREFIX . 'story_to_store AS sts ON s.story_id = sts.story_id AND sts.store_id = ' . (int) $this->config->get('config_store_id') . '
                LEFT JOIN ' . DB_PREFIX . 'story_to_tag AS stt ON s.story_id = stt.story_id
                LEFT JOIN ' . DB_PREFIX . 'story_tag AS st ON st.tag_id = stt.tag_id AND st.language_id = ' . (int) $this->config->get('config_language_id') . '
                WHERE s.status = 1 AND sd.title <> ""';

        if (isset($options['filter_tag_ids'])) {
            $sql .= 'AND s.story_id IN (
                         SELECT DISTINCT(sd.story_id)
                         FROM ' . DB_PREFIX . 'story_description AS sd
                         INNER JOIN ' . DB_PREFIX . 'story_to_tag AS stt ON sd.story_id = stt.story_id
                         WHERE sd.language_id = ' . (int) $this->config->get('config_language_id') . '
                               AND stt.tag_id IN (' . implode(',', $options['filter_tag_ids']) . ')
                     )';
        }

        if (isset($options['filter_story_ids'])) {
            $sql .= " AND s.story_id IN ( " . implode(',', (array) $options['filter_story_ids']) . " )";
        }

        $sql .= ' GROUP BY s.story_id';

        if (!isset($options['sort']) || empty($options['sort'])) {
            $options['sort'] = 's.date_added DESC';
        }

        if (isset($options['filter_sticky_first']) && $options['filter_sticky_first']) {
            $sql .= ' ORDER BY s.sticky DESC, ' . $options['sort'];
        } else {
            $sql .= ' ORDER BY ' . $options['sort'];
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

    public function getOne($id)
    {
        if (isset($this->fetched_stories[$id])) {
            return $this->fetched_stories[$id];
        }

        $sql = 'SELECT s.*, sd.*, GROUP_CONCAT(st.name, "<=>", st.tag_id SEPARATOR "!*!") AS tags
                FROM ' . DB_PREFIX . 'story AS s
                INNER JOIN ' . DB_PREFIX . 'story_description AS sd ON s.story_id = sd.story_id AND sd.language_id = ' . (int) $this->config->get('config_language_id') . '
                INNER JOIN ' . DB_PREFIX . 'story_to_store AS sts ON s.story_id = sts.story_id AND sts.store_id = ' . (int) $this->config->get('config_store_id') . '
                LEFT JOIN ' . DB_PREFIX . 'story_to_tag AS stt ON s.story_id = stt.story_id
                LEFT JOIN ' . DB_PREFIX . 'story_tag AS st ON st.tag_id = stt.tag_id AND st.language_id = ' . (int) $this->config->get('config_language_id') . '
                WHERE s.story_id = ' . (int) $id . ' AND s.status = 1
                GROUP BY s.story_id';

        $this->fetched_stories[$id] = $this->db->query($sql)->row;

        return $this->fetched_stories[$id];
    }

    public function getTag($id)
    {
        if (is_array($id)) {
            return $tags = $this->dbHelper->getRecords('story_tag', array('wherein' => array('tag_id', $id)));
        }

        return $this->dbHelper->getRecord('story_tag', array('tag_id' => $id));
    }

    public function incrementViews($id)
    {
        $this->dbHelper->query('UPDATE ' . DB_PREFIX . 'story SET views = views+1 WHERE story_id = ' . (int) $id);
    }
}
