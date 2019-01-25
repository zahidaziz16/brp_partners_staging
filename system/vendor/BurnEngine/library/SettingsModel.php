<?php

class TB_SettingsModel implements ArrayAccess
{
    /**
     * @var array
     */
    private $container = array();

    /**
     * @var TB_DbSettingsHelper
     */
    protected $dbSettingsHelper;

    /**
     * @var string
     */
    protected $settings_group;

    /**
     * @var int
     */
    protected $store_id;

    protected $key_postfix;

	public function __construct(TB_DbSettingsHelper $dbSettingsHelper, $settings_group, $store_id, $key_postfix = null)
    {
		$this->dbSettingsHelper = $dbSettingsHelper;
        $this->settings_group   = $settings_group;
        $this->store_id         = $store_id;
        $this->key_postfix      = $key_postfix;
	}

    /**
     * @param $scope
     * @param bool $raw
     * @param null $store_id
     *
     * @return null|mixed
     */
    public function getScopeSettings($scope, $raw = false, $store_id = null)
    {
        $key = $scope;
        
        if (null !== $this->key_postfix) {
            $key .= '_' . $this->key_postfix;
        }

        if (null === $store_id) {
            $store_id = $this->store_id;
        }

        if ($raw) {
            return $this->dbSettingsHelper->getKey($key, $store_id, $this->settings_group);
        }

        if (!isset($this->container[$scope])) {
            $this->container[$scope] = $this->dbSettingsHelper->getKey($key, $store_id, $this->settings_group);
        }

        return $this->container[$scope];
    }

    public function getScopeSetting($scope, $key, $default = null, $raw = false, $store_id = null)
    {
        $settings = $this->getScopeSettings($scope, $raw, $store_id);

        return isset($settings[$key]) ? $settings[$key] : $default;
    }

    public function setScopeSetting($scope, $key, $value)
    {
        $this->container[$scope][$key] = $value;
    }

    public function removeScopeSetting($scope, $key)
    {
        unset($this->container[$scope][$key]);
    }

    public function setScopeSettings($scope, $settings)
    {
        $this->container[$scope] = $settings;
    }

    public function persistScopeSettings($scope, $store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->store_id;
        }

        $key = $scope;
        if (null !== $this->key_postfix) {
            $key .= '_' . $this->key_postfix;
        }

        $this->dbSettingsHelper->persistKey($key, $store_id, $this->settings_group, $this[$scope]);
    }

    public function persistCustomSettings($settings, $scope, $store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->store_id;
        }

        $key = $scope;
        if (null !== $this->key_postfix) {
            $key .= '_' . $this->key_postfix;
        }

        $this->dbSettingsHelper->persistKey($key, $store_id, $this->settings_group, $settings);
    }

    public function setAndPersistScopeSettings($scope, $settings, $store_id = null)
    {
        $this->setScopeSettings($scope, $settings);
        $this->persistScopeSettings($scope, $store_id);
    }

    public function deleteScopeSettings($scope, $store_id = null)
    {
        if (null === $store_id) {
            $store_id = $this->store_id;
        }

        if (null !== $this->key_postfix) {
            $scope .= '_' . $this->key_postfix;
        }

        $this->dbSettingsHelper->deleteKey($scope, $store_id, $this->settings_group);
        unset($this->container[$scope]);
    }

    public function getKeys()
    {
        return $this->dbSettingsHelper->getKeys($this->store_id, $this->settings_group);
    }

    public function getValues()
    {
        return $this->dbSettingsHelper->getGroup($this->settings_group, $this->store_id);
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function offsetSet($offset, $value)
    {
        $this->container[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetExists($offset)
    {
        if (isset($this->container[$offset])) {
            return true;
        }

        $result = $this->getScopeSettings($offset);

        return null !== $result;
    }

    /**
     * @param $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        if (isset($this->container[$offset])) {
            return $this->container[$offset];
        }

        return $this->getScopeSettings($offset);
    }
}