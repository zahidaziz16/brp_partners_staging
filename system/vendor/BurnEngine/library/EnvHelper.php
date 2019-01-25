<?php

class TB_EnvHelper
{
    /**
     * @var TB_EnvHelper
     */
    protected static $instance;

    /**
     * @var Registry
     */
    protected $registry;

    protected function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param Registry $registry
     *
     * @throws InvalidArgumentException
     * @return TB_EnvHelper
     */
    public static function getInstance(Registry $registry = null)
    {
        if (!isset(self::$instance)) {
            if (null === $registry) {
                throw new InvalidArgumentException('You must supply Registry instance');
            }
            self::$instance = new self($registry);
        }

        return self::$instance;
    }

    public function getEngineConfig($basename)
    {
        static $config = null;

        if (null !== $config) {
            return $config;
        }

        $config_file = DIR_SYSTEM . 'vendor/' . $basename . '/config/engine.php';

        if (!file_exists($config_file)) {
            throw new Exception('The theme config file cannot be found: ' . $config_file);
        }

        $config = require $config_file;

        if (!is_array($config)) {
            throw new Exception('Engine config structure is not array');
        }

        return $config;
    }

    public function getThemeConfig($theme_id)
    {
        static $config = null;

        if (null !== $config) {
            return $config;
        }

        $config_file = DIR_SYSTEM . 'vendor/' . TB_BASENAME . '/themes/' . $theme_id . '/config.php';
        if (!file_exists($config_file)) {
            throw new Exception('The theme config file cannot be found: ' . $config_file);
        }

        $config = require $config_file;

        if (!is_array($config)) {
            throw new Exception('Theme config structure is not array - ' . $config_file . "\n\n" . var_export($config, true));
        }

        return $config;
    }

    public function getCurrentLanguage($key = null, $fallback_language_code = 'en-gb')
    {
        static $current = null;

        if (null === $current) {

            if (!version_compare(VERSION, '2.2.0.0', '>=') && ($fallback_language_code == 'en-gb' || $fallback_language_code == 'english')) {
                $fallback_language_code = 'en';
            }

            /** @var $config Config */
            $config = $this->registry->get('config');

            if (version_compare(VERSION, '2.2.0.0', '>=')) {
                $current_language_id = $config->get('config_language_id');

                // If someone changes the language code the config_admin_language db settings remains with the old one
                if (!empty($current_language_id)) {
                    foreach ($this->getLanguages() as $language) {
                        if ($language['id'] == $current_language_id) {
                            $current = $language;
                        }
                    }
                }

                if (null === $current) {
                    foreach ($this->getLanguages() as $language) {
                        if (strtolower($language['code']) == $fallback_language_code) {
                            $current = $language;
                        }
                    }
                }

                if (null === $current) {
                    foreach ($this->getLanguages() as $language) {
                        $current = $language;

                        break;
                    }
                }
            } else {
                $current_language_code = $config->get('config_language');

                if (empty($current_language_code)) {
                    $current_language_code = $fallback_language_code;
                }

                foreach ($this->getLanguages() as $language) {
                    if ($language['code'] == $current_language_code) {
                        $current = $language;
                    }
                }
            }
        }

        return $key !== null ? $current[$key] : $current;
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

        /** @var $db DB */
        $db = $this->registry->get('db');

        $language_data = array();

        $query = $db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");
        foreach ($query->rows as $result) {
            if (version_compare(VERSION, '2.2.0.0', '>=') ) {
                $directory = $result['code'];
                $filename  = $result['code'];
            } else {
                $directory = $result['directory'];
                $filename  = isset($result['filename']) ? $result['filename'] : $result['directory'];
            }

            $image = '';
            if (!empty($result['image'])) {
                $image = $result['image'];
            }

            $language_data[$result['code']] = array(
                'id'          => (int) $result['language_id'],
                'language_id' => (int) $result['language_id'],
                'name'        => $result['name'],
                'code'        => $result['code'],
                'locale'      => $result['locale'],
                'image'       => $image,
                'directory'   => $directory,
                'filename'    => $filename,
                'sort_order'  => $result['sort_order'],
                'status'      => $result['status']
            );
        }

        return $language_data;
    }
}