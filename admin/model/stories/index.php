<?php

require_once tb_modification(DIR_SYSTEM . 'vendor/stories/model.php');

class ModelStoriesIndex extends ModelStoriesModel
{
	public function insert($data)
    {
        $this->dbHelper->insert('story', array(
            'image'      => strlen($data['image']) && file_exists(DIR_IMAGE . $data['image']) ? $data['image'] : '',
            'status'     => (int) $data['status'],
            'sticky'     => (int) $data['sticky'],
            'views'      => 0,
            'date_added' => date('Y-m-d H:i:s', time())
        ));

        $story_id = $this->dbHelper->getLastId();

        if (defined('TB_SEO_MOD') && class_exists('TB_Engine') && TB_Engine::hasInstance()) {
            $data = TB_Engine::instance()->getThemeData()->insertItemLanguageKeywords($data, 'story', $story_id);
        }

        $this->insertLang($story_id, $data['lang']);
        $this->insertKeyword($story_id, $data['keyword'], $data['lang']);
        $this->insertStores($story_id, $data['stores']);
        $this->insertLayouts($story_id, $data['layouts']);
	}

	public function update($story_id, $data)
    {
        settype($story_id, 'integer');

        if (empty($story_id)) {
            return;
        }

        $this->dbHelper->update('story',
            array(
                'image'        => strlen($data['image']) && file_exists(DIR_IMAGE . $data['image']) ? $data['image'] : '',
                'status'       => (int) $data['status'],
                'sticky'       => (int) $data['sticky'],
                'date_updated' => date('Y-m-d H:i:s', time())
            ),
            array('story_id' => $story_id)
        );

        if (defined('TB_SEO_MOD') && class_exists('TB_Engine') && TB_Engine::hasInstance()) {
            $data = TB_Engine::instance()->getThemeData()->insertItemLanguageKeywords($data, 'story', $story_id);
        }

        $this->insertLang($story_id, $data['lang']);
        $this->insertKeyword($story_id, $data['keyword'], $data['lang']);
        $this->insertStores($story_id, $data['stores']);
        $this->insertLayouts($story_id, $data['layouts']);
	}

    public function insertStoriesKeyword($keyword)
    {
        $this->dbHelper->delete('url_alias', array('query' => 'stories/index'));

        if (empty($keyword)) {
            return;
        }

        $this->dbHelper->insert('url_alias', array(
            'query'   => 'stories/index',
            'keyword' => $keyword
        ));
    }

    public function getStoriesKeyword()
    {
        return $this->dbHelper->getValue('url_alias', 'keyword', array('query' => 'stories/index'));
    }

    protected function insertKeyword($story_id, $keyword, $lang_data)
    {
        $settings = $this->getSettings();
        $keyword = trim((string) $keyword);

        if (defined('TB_SEO_MOD') && class_exists('TB_Engine') && TB_Engine::hasInstance()) {
            $settings['auto_seo_url'] = 0;
            $settings['skip_existing_seo_url'] = 0;
        }

        if (!strlen($keyword) && !$settings['auto_seo_url']) {
            $this->dbHelper->delete('url_alias', array('query' => 'story_id=' . $story_id));

            return;
        }

        if ($settings['skip_existing_seo_url'] && $this->dbHelper->getValue('url_alias', 'keyword', array('query' => 'story_id=' . $story_id))) {
            return;
        }

        if ($settings['auto_seo_url']) {
            $language_id = $this->getModel()->getDefaultLanguage('language_id');
            $title = $lang_data[$language_id]['title'];
            $keyword = URLify::filter($title);
            if ($this->dbHelper->getValue('url_alias', 'keyword', array('keyword' => $keyword))) {
                $i = 1;
                while ($this->dbHelper->getValue('url_alias', 'keyword', array('keyword' => $keyword . '-' . $i))) {
                    $i++;
                }
                $keyword .= '-' . $i;
            }
        }

        if (!empty($keyword)) {
            $this->dbHelper->delete('url_alias', array('query' => 'story_id=' . $story_id));
            $this->dbHelper->insert('url_alias', array(
                'query'   => 'story_id=' . $story_id,
                'keyword' => $keyword
            ));
        }
    }

    protected function insertLang($story_id, $lang_data)
    {
        $this->dbHelper->delete('story_description', array('story_id' => $story_id));
        $this->dbHelper->delete('story_to_tag', array('story_id' => $story_id));

        foreach ($lang_data as $language_id => $story) {
            $this->dbHelper->insert('story_description', array(
                'story_id'         => $story_id,
                'language_id'      => (int) $language_id,
                'title'            => trim((string) $story['title']),
                'meta_title'       => trim((string) $story['meta_title']),
                'meta_description' => trim((string) $story['meta_description']),
                'teaser'           => trim((string) $story['teaser']),
                'description'      => trim((string) $story['description'])
            ));

            $story_tags = trim((string) $story['tags']);
            if(!empty($story_tags)) {
                foreach (array_map('trim', explode(',', $story_tags)) as $tag_name) {
                    $db_tag = $this->dbHelper->getRecord('story_tag', array('language_id' => $language_id, 'name' => $tag_name));
                    if (!$db_tag) {
                        $tag_id = $this->getModel('tag')->insert(array(
                            'language_id'      => $language_id,
                            'name'             => $tag_name,
                            'description'      => '',
                            'meta_title'       => '',
                            'meta_description' => '',
                            'status'           => 1,
                            'keyword'          => ''
                        ));
                    } else {
                        $tag_id = $db_tag['tag_id'];
                    }

                    $this->dbHelper->insert('story_to_tag', array('story_id' => $story_id, 'tag_id' => $tag_id));
                }
            }
        }
    }

    protected function insertStores($story_id, array $stores)
    {
        $this->dbHelper->delete('story_to_store', array('story_id' => $story_id));

        $data = array();
        foreach ($stores as $store_id) {
            $data[] = array($story_id,  $store_id);
        }

        if ($data) {
            $this->dbHelper->insertMultiple('story_to_store', array('story_id', 'store_id'), $data);
        }
    }

    protected function insertLayouts($story_id, $store_layouts)
    {
        $this->dbHelper->delete('story_to_layout', array('story_id' => $story_id));

        $data = array();
        foreach ($store_layouts as $store_id => $layout) {
            if (!empty($layout['layout_id'])) {
                $data[] = array($story_id, $store_id, $layout['layout_id']);
            }
        }

        if ($data) {
            $this->dbHelper->insertMultiple('story_to_layout', array('story_id', 'store_id', 'layout_id'), $data);
        }
    }

    public function getStories(array $options = array())
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS sd.*, s.*, GROUP_CONCAT(st.name) AS tags ';

        if (isset($options['added_story_ids']) && !empty($options['added_story_ids'])) {
            $sql .= ", IF( s.story_id IN ( " . implode(',', (array) $options['added_story_ids']) . " ) ,  '1',  '0' ) AS added ";
        } else {
            $options['added_story_ids'] = null;
        }

        $sql .= 'FROM ' . DB_PREFIX . 'story AS s
                 LEFT JOIN ' . DB_PREFIX . 'story_description AS sd ON s.story_id = sd.story_id AND sd.language_id = ' . (int) $this->config->get('config_language_id') . '
                 LEFT JOIN ' . DB_PREFIX . 'story_to_tag AS stt ON s.story_id = stt.story_id
                 LEFT JOIN ' . DB_PREFIX . 'story_tag AS st ON st.tag_id = stt.tag_id AND st.language_id = ' . (int) $this->config->get('config_language_id') . '
                 WHERE 1';

        if (isset($options['filter_status']) && strlen($options['filter_status'])) {
            $sql .= ' AND s.status = ' . (int) $options['filter_status'];
        }

        if (isset($options['filter_sticky']) && $options['filter_sticky']) {
            $sql .= ' AND s.sticky = 1';
        }

        if (isset($options['filter_text']) && strlen($options['filter_text'])) {
            $sql .= ' AND sd.title LIKE "%' . (string) $options['filter_text'] . '%" OR st.description LIKE "%' . $options['filter_text'] . '%"';
        }

        if (isset($options['filter_tags']) && strlen($options['filter_tags'])) {
            $sql .= ' AND st.name IN ( "' . implode('","', (array) $options['filter_tags']) . '" )';
        }

        if ($options['added_story_ids'] && isset($options['filter_selected']) && !is_null($options['filter_selected'])) {
            $sql .= " AND s.story_id IN ( " . implode(',', (array) $options['added_story_ids']) . " )";
        }

        $sql .= ' GROUP BY s.story_id';

        if (!isset($options['sort'])) {
            $options['sort'] = 's.date_added';
        }

        $sql .= ' ORDER BY ' . $options['sort'];

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

        $stories = array();
        $empty_titles_ids = array();

        foreach ($this->db->query($sql)->rows as $row) {
            $stories[$row['story_id']] = $row;
            if (empty($row['title'])) {
                $empty_titles_ids[] = $row['story_id'];
            }
        }

        if (!empty($empty_titles_ids)) {
            $sql = 'SELECT DISTINCT(story_id), sd.*,
                      (SELECT GROUP_CONCAT(st.name)
                       FROM ' . DB_PREFIX . 'story_tag AS st
                       INNER JOIN ' . DB_PREFIX . 'story_to_tag AS stt ON st.tag_id = stt.tag_id
                       WHERE stt.story_id = sd.story_id
                       GROUP BY stt.story_id
                      ) as tags
                    FROM ' . DB_PREFIX . 'story_description AS sd
                    WHERE title <> "" AND story_id IN (' . implode(',', $empty_titles_ids) . ')';

            foreach ($this->db->query($sql)->rows as $row) {
                $stories[$row['story_id']] = array_merge($stories[$row['story_id']], $row);
            }
        }

        return $stories;
    }

	public function getOne($id)
    {
        if ($story = $this->dbHelper->getRecord('story', array('story_id' => (int) $id))) {

            $keyword = $this->dbHelper->getValue('url_alias', 'keyword', array('query' => 'story_id=' . (int) $id));
            $story['keyword'] = $keyword ? $keyword : '';

            $stores = $this->dbHelper->getColumn('story_to_store', 'store_id', array('story_id' => (int) $id));
            $story['stores'] = $stores ? $stores : array();

            $story['layouts'] = array();
            $store_layouts = $this->dbHelper->getRecords('story_to_layout', array('story_id' => (int) $id));
            if ($store_layouts) {
                foreach ($store_layouts as $row) {
                    $story['layouts'][$row['store_id']] = $row['layout_id'];
                }
            }

            return $story;
        }

        return false;
	}

    public function getAllStores()
    {
        static $stores = null;

        if (null === $stores) {
            $stores = $this->getModel('setting/store')->getStores();
            $stores = array_merge(array(0 => array(
                'store_id' => 0,
                'name' => $this->config->get('config_name')
            )), $stores);
        }

        return $stores;
    }

	public function getLang($id)
    {
        if (!$stories = $this->dbHelper->getRecords('story_description', array('story_id' => (int) $id))) {
            return false;
        }

        $tags = array();
        if ($tag_ids = $this->dbHelper->getColumn('story_to_tag', 'tag_id', array('story_id' => (int) $id))) {
            $tags = $this->dbHelper->getRecords('story_tag', array('wherein' => array('tag_id', $tag_ids)));
        }

        $languages = $this->getLanguages();

        $result = array();
		foreach ($stories as $story) {

            if (!isset($languages[$story['language_id']])) {
                continue;
            }

            $story_tags = array();
            foreach ($tags as $tag) {
                if ($tag['language_id'] == $story['language_id']) {
                    $story_tags[] = $tag['name'];
                }
            }

			$result[$story['language_id']] = array(
				'title'            => $story['title'],
                'meta_title'       => $story['meta_title'],
                'meta_description' => $story['meta_description'],
                'teaser'           => $story['teaser'],
				'description'      => $story['description'],
                'tags'             => implode(',', $story_tags)
			);
		}

		return $result;
	}

    public function getLanguages($cache = true)
    {
        static $language_data = null;

        if (!$cache) {
            $language_data = null;
        }

        if (null !== $language_data) {
            return $language_data;
        }

        $language_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");
        foreach ($query->rows as $result) {
            $image_path = version_compare(VERSION, '2.2.0.0', '>=') ? 'language/' : 'view/image/flags/';
            if (version_compare(VERSION, '2.2.0.0', '>=')) {
                $result['image'] = $result['code'] . '/' . $result['code'] . '.png';
            }

            $language_data[$result['language_id']] = array(
                'id'          => (int) $result['language_id'],
                'language_id' => (int) $result['language_id'],
                'name'        => $result['name'],
                'code'        => $result['code'],
                'locale'      => $result['locale'],
                'image'       => $result['image'],
                'image_url'   => $image_path . $result['image'],
                'directory'   => $result['directory'],
                'filename'    => isset($result['filename']) ? $result['filename'] : $result['directory'],
                'sort_order'  => $result['sort_order'],
                'status'      => $result['status']
            );
        }

        return $language_data;
    }

	public function deleteOne($id)
    {
        $this->dbHelper->delete('story', array('story_id' => $id));
        $this->dbHelper->delete('story_description', array('story_id' => $id));
        $this->dbHelper->delete('url_alias', array('query' => 'story_id=' . (int) $id));
	}
}
