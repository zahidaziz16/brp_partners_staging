<?php

class TB_DataBag implements ArrayAccess
{
    /**
     * @var array
     */
    protected $lazy_vars;
    /**
     * @var array
     */
    protected $callables;

    /**
     * @var array
     */
    protected $container;

    public function __construct()
    {
        $this->callables = array();
        $this->container = array();
    }

    public function __call($name, $args)
    {
        if (!isset($this->callables[$name])) {
            throw new Exception('This method is not added as callable: ' . $name);
        }

        switch(count($args))
        {
            case 0:
                return $this->callables[$name][0]->{$this->callables[$name][1]}();
            case 1:
                return $this->callables[$name][0]->{$this->callables[$name][1]}($args[0]);
            case 2:
                return $this->callables[$name][0]->{$this->callables[$name][1]}($args[0], $args[1]);
            case 3:
                return $this->callables[$name][0]->{$this->callables[$name][1]}($args[0], $args[1], $args[2]);
            default:
                return call_user_func_array($this->callables[$name], $args);
        }
    }

    public function addCallable($callable, $synonym = null)
    {
        if (is_callable($callable, true, $name)) {
            if (!strpos($name, '::')) {
                throw new Exception('You must specify a class method when adding a callable ' . $name);
            }
            list(, $method_name) = explode('::', $name);
            if (null !== $synonym) {
                if (isset($this->callables[$synonym])) {
                    throw new Exception('This synonym has been already defined: ' . $synonym);
                }
                $this->callables[$synonym] = $callable;
            } else
            if (!isset($this->callables[$method_name])) {
                $this->callables[$method_name] = $callable;
            } else {
                throw new Exception('This callablle has been already defined: ' . $method_name);
            }
        } else {
            throw new Exception('You must specify a class method when adding a callable ' . $callable);
        }
    }

    public function unsetCallable($name) {
        if (isset($this->callables[$name])) {
            unset($this->callables[$name]);
        }
    }

    public function hasCallable($name) {
        return isset($this->callables[$name]);
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

    public function setLazyVar($name, $callable)
    {
        if (is_callable($callable)) {
            $this->lazy_vars[$name] = $callable;
        } else {
            throw new Exception('You must specify a class method when adding a callable ' . $callable);
        }
    }

    public function get($name, $default = null)
    {
        $result = $this->offsetGet($name);
        if (null == $result && null !== $default) {
            $result = $default;
        }

        return $result;
    }

    public function set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->container[$offset])) {
            return $this->container[$offset];
        } else
        if (isset($this->lazy_vars[$offset])) {
            $this->container[$offset] = call_user_func($this->lazy_vars[$offset]);
            unset($this->lazy_vars[$offset]);

            return $this->container[$offset];
        }

        return null;
    }

    public function importVars(array $vars)
    {
        foreach ($vars as $key => $value) {
            $this[$key] = $value;
        }
    }

    public function exportVars()
    {
        return $this->container;
    }

    public function exportScalarVars()
    {
        $result = array();

        foreach ($this->container as $key => $value) {
            if (is_array($value) || is_scalar($value)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}