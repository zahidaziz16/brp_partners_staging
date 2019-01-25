<?php

class Theme_DefaultModel extends TB_ExtensionModel
{
    public function getLayouts()
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "layout";
        $query = $this->db->query($sql);

        $result = array();
        foreach ($query->rows as $row) {
            $result[$row['layout_id']] = $row;
        }

        return $result;
    }

    public function getLayoutIdByName($layout_name)
    {
        static $layouts = array();

        if (isset($layouts[$layout_name])) {
            return $layouts[$layout_name];
        }

        $sql = "SELECT layout_id FROM " . DB_PREFIX . "layout WHERE LOWER(name) = '" . $this->db->escape(strtolower($layout_name)) . "'";
        $query = $this->db->query($sql);

        $layouts[$layout_name] = isset($query->row['layout_id']) ? (int) $query->row['layout_id'] : 0;

        return $layouts[$layout_name];
    }

    public function getLayoutNameById($layout_id)
    {
        static $layouts = array();

        if (!isset($layouts[$layout_id])) {
            $layouts[$layout_id] = '';
            $layout = $this->db->query('SELECT name FROM ' . DB_PREFIX . 'layout WHERE layout_id = ' . (int) $layout_id);
            if (!empty($layout->name)) {
                $layouts[$layout_id] = $layout->name;
            }
        }

        return $layouts[$layout_id];
    }

    public function getInformationPages($id = null)
    {
        static $pages = null;

        if (null === $pages) {
            $sql = 'SELECT *
                    FROM ' . DB_PREFIX . 'information i
                    LEFT JOIN ' . DB_PREFIX . 'information_description id ON (i.information_id = id.information_id)
                    LEFT JOIN ' . DB_PREFIX . 'information_to_store i2s ON (i.information_id = i2s.information_id)
                    WHERE id.language_id = ' . $this->context->getCurrentLanguage('id') . '
                          AND i2s.store_id = ' . $this->context->getStoreId() . '
                          AND i.status = 1
                          ORDER BY i.sort_order, LCASE(id.title) ASC';

            $pages = array();
            foreach ($this->db->query($sql)->rows as $page) {
                $page['id'] = $page['information_id'];
                $pages[$page['information_id']] = $page;
            }
        }

        if (null !== $id) {
            return isset($pages[$id]) ? $pages[$id] : false;
        }

        return $pages;
    }

    public function alignImagesAttributes($text)
    {
        if (function_exists('mb_convert_encoding')) {
            $text = mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8');
        }

        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $text . '</div>');

        /** @var DOMElement $item */
        foreach ($dom->getElementsByTagName('img') as $item) {
            $attributes = array();
            foreach ($item->attributes as $attr) {
                $attributes[$attr->nodeName] = $attr->nodeValue;
            }

            if (!empty($attributes['src'])) {
                foreach ($attributes as $attr_name => $attr_value) {
                    $item->removeAttribute($attr_name);
                }
                $item->setAttribute('src', $attributes['src']);

                $float_class = '';

                if (isset($attributes['class']) && false !== stripos($attributes['class'], 'left')) {
                    $float_class .= ' pull-left';
                }
                if (isset($attributes['class']) && false !== stripos($attributes['class'], 'right')) {
                    $float_class .= ' pull-right';
                }

                if (empty($attributes['width']) || empty($attributes['height'])) {
                    if (!empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
                        $context = stream_context_create(array(
                            'http' => array(
                                'header' => 'Authorization: Basic ' . base64_encode("{$_SERVER['PHP_AUTH_USER']}:{$_SERVER['PHP_AUTH_PW']}")
                            )
                        ));
                        $image = file_get_contents($attributes['src'], false, $context);
                        list($attributes['width'], $attributes['height']) = getimagesizefromstring($image);
                    } else {
                        if ($image_size = @getimagesize($attributes['src'])) {
                            list($attributes['width'], $attributes['height']) = $image_size;
                        }
                    }
                }

                if (!empty($attributes['width'])) {
                    $attributes['style']    = 'margin-top: -' . (round($attributes['height'] / $attributes['width'], 4) * 100) . '%;';
                    $attributes['data-src'] = $attributes['src'];
                    $attributes['src']      = $this->context->getThemeCatalogImageUrl() . 'pixel.gif';
                }

                if (!isset($attributes['class'])) {
                    $attributes['class'] = '';
                }
                if (empty($attributes['class']) || !in_array('lazyload', explode(' ', $attributes['class']))) {
                    $attributes['class'] = trim($attributes['class'] . ' lazyload');
                }

                foreach ($attributes as $attr_name => $attr_value) {
                    $item->setAttribute($attr_name, $attr_value);
                }

                if ($item->parentNode->parentNode->nodeName != 'span' && !empty($attributes['width'])) {
                    $outerSpan = $dom->createElement('span');
                    $outerSpan->setAttribute('class', 'image-holder' . $float_class);
                    $outerSpan->setAttribute('style', 'max-width: ' . $attributes['width'] . 'px;');
                    $item->parentNode->replaceChild($outerSpan, $item);

                    $innerSpan = $dom->createElement('span');
                    $innerSpan->appendChild($item);
                    $innerSpan->setAttribute('style', 'padding-top: ' . (round($attributes['height'] / $attributes['width'], 4) * 100) . '%;');

                    $outerSpan->appendChild($innerSpan);
                }
            }
        }

        return $this->DOMInnerHTML($dom->getElementsByTagName('div')->item(0));
    }

    protected function DOMInnerHTML(DOMNode $element)
    {
        $innerHTML = '';

        foreach ($element->childNodes as $child) {
            $dom = new DOMDocument();
            $dom->appendChild($dom->importNode($child, true));
            $innerHTML .= trim($dom->saveHTML());
        }


        return html_entity_decode(html_entity_decode($innerHTML, ENT_COMPAT, 'UTF-8'), ENT_COMPAT, 'UTF-8');
    }

    public function getStockStatuses()
    {
        $stock_status_data = $this->engine->getOcCache()->get('stock_status.' . $this->context->getCurrentLanguage('id'));

        if (!$stock_status_data) {
            $query = $this->db->query("SELECT stock_status_id, name FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . $this->context->getCurrentLanguage('id') . "' ORDER BY name");

            $stock_status_data = $query->rows;

            $this->engine->getOcCache()->set('stock_status.' . $this->context->getCurrentLanguage('id'), $stock_status_data);
        }

        return $stock_status_data;
    }

    public function getPresetIds()
    {
        static $preset_ids = null;

        if (null !== $preset_ids || $preset_ids = $this->engine->getCacheVar('preset_ids')) {
            return $preset_ids;
        }

        $user_presets = $this->engine->getSettingsModel('preset', 0)->getValues();
        $theme_presets = $this->engine->getSettingsModel('preset_' . $this->engine->getThemeId(), 0)->getValues();

        $preset_ids = array();

        foreach (array_merge($user_presets, $theme_presets) as $preset) {
            $preset_ids[] = $preset['id'];
        }

        $this->engine->setCacheVar('preset_ids', $preset_ids);

        return $preset_ids;
    }

    public function getInstalledOcModules()
    {
        if ($this->context->getArea() == 'admin') {
            if ($this->engine->gteOc2()) {
                return $this->getOcModel('extension/extension')->getInstalled('module');
            } else {
                return $this->getOcModel('setting/extension')->getInstalled('module');
            }
        }

        if ($this->engine->gteOc2()) {
            $rows = $this->getOcModel('extension/extension')->getExtensions('module');
        } else {
            $rows = $this->getOcModel('setting/extension')->getExtensions('module');
        }

        return array_column($rows, 'code');
    }

    public function getProductOptionStyles()
    {
        return array(
            'style_1' => 'Default',
            'style_2' => 'Button'
        );
    }

    public function keyExists($db_key, $group, $store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->engine->getContext()->getStoreId();
        }

        return $this->engine->getDbSettingsHelper()->keyExists($db_key, $store_id, $group);
    }

    public function getSetting($key, $default = null)
    {
        return $this->engine->getThemeSettingsModel()->getScopeSetting($this->engine->getThemeId(), $key, $default);
    }

    public function getSettings($raw = false)
    {
        return $this->engine->getThemeSettingsModel()->getScopeSettings($this->engine->getThemeId(), $raw);
    }

    public function setSettings($settings)
    {
        $this->engine->getThemeSettingsModel()->setScopeSettings($this->engine->getThemeId(), $settings);
    }

    public function setAndPersistSettings(array $settings)
    {
        $this->setSettings($settings);
        $this->engine->getThemeSettingsModel()->persistScopeSettings($this->engine->getThemeId());
    }
}