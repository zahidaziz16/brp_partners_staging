<?php

require_once TB_THEME_ROOT . '/library/ViewSlotEvent.php';

class TB_ViewSlot
{
    protected $openSlots   = array();
    /**
     * @var TB_ViewSlotEvent[]
     */
    protected $events      = array();
    protected $contents    = array();
    protected $groupMap    = array();
    protected $openJsSlots = 0;
    protected $jsContents  = '';

    /**
     * @var sfEventDispatcher
     */
    protected $eventDispatcher;

    public function __construct(sfEventDispatcher $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    public function __destruct()
    {
        if (count($this->openSlots) > 0 || $this->openJsSlots > 0) {
            trigger_error('You have unclosed slots : ' . var_export($this->openSlots, true), E_USER_ERROR);
            echo "<br />\nPlease, check your script for errors.\n<br /> Terminating sript execution.";

            exit();
        }
    }

    /**
     * Filters a variable that has been passed from the view.
     *
     * @param string $name The variable name
     * @param mixed $value
     * @param array $params Additional parameters that will be passed to the filter event
     *
     * @return mixed
     */
    public function filter($name, &$value, array $params = array())
    {
        foreach (explode('|', $name) as $filter_name) {
            $event = new sfEvent($this, $filter_name, $params);

            $this->eventDispatcher->filter($event, $value);
            $result = $event->getReturnValue();

            if (null !== $result) {
                $value = $result;
            }
        }

        return $value;
    }

    /**
     * Starts a new slot.
     *
     * This method starts an output buffer that will be closed and echoed when the stop() method is called.
     * Through the event you can insert content before and after the slot. With phpquery you can also modify the
     * content that has been captured within the slot.
     *
     * @param string $name The slot name
     * @param array $params Additional parameters that will be passed to the event
     *
     * @throws InvalidArgumentException
     * @return array
     *
     */
    public function start($name = '', array $params = array())
    {
        if (empty($name)) {
            //$name = 'tempSlot_' . mt_rand();
        }

        if (in_array($name, $this->openSlots)) {
            throw new InvalidArgumentException(sprintf('A slot named "%s" is already started.', $name));
        }

        if (isset($params['group'])) {
            if (!isset($this->groupMap[$params['group']])) {
                $this->groupMap[$params['group']] = array();
            }
            $this->groupMap[$params['group']][] = $name;
        }

        if (isset($params['filter'])) {
            $filter_data = $params['filter'];
            $filter_name = array_shift($filter_data);
            unset($params['filter']);

            $this->filter($filter_name, $filter_data, $params);
        }

        $this->openSlots[] = $name;
        $this->events[$name] = new TB_ViewSlotEvent($this, 'viewSlot:' . $name, $params);

        ob_start();
        ob_implicit_flush(0);
    }

    /**
     * Stops and outputs the slot's captured (and optionally modified) content.
     *
     * @param bool $capture
     * @param bool $stack
     * @return string
     * @throws LogicException if no slot has been started
     */
    public function stop($capture = false, $stack = false)
    {
        if (!$this->openSlots) {
            throw new LogicException('No slot started.');
        }

        $name    = array_pop($this->openSlots);
        $content = ob_get_clean();
        $event   = $this->events[$name];

        $event->setContent(trim($content));
        $this->eventDispatcher->notify($event);
        unset($this->events[$name]);

        if ($capture) {
            if (!isset($this->contents[$name])) {
                $this->contents[$name] = '';
            }
            if ($stack) {
                $this->contents[$name] .= trim($event->getAllContent());
            } else {
                $this->contents[$name] = trim($event->getAllContent());
            }
        } else {
            echo $event->getAllContent();
        }

        return $name;
    }

    // If this method is used in the template instead of start(), the system widget render method will remove the route
    // part from the slot name. It will then render the system slot without its prefix. This is useful for templates
    // that are used by more than one route - common/success, error/not_found etc. It is also need when caching is used
    // because the settings cache stores the system widgets with their slots for the current area, but the area is used
    // by many routes and the system widget render method cannot find the slot content by slotFullName.
    public function startSystem($name, array $params = array())
    {
        if (!isset($params['group'])) {
            $params['group'] = 'system';
        }

        $this->start($name, $params);
    }

    /**
     * Defines a view flag. Basically works like invoking start() and stop() consecutively.
     * With the help of the view_flag event you can insert content before and after the flag.
     *
     * @param string $name The flag name
     * @param array $params Additional parameters that will be passed to the event
     */
    public function flag($name, array $params = array())
    {
        $event = new TB_ViewSlotEvent($this, $name, $params);
        $this->eventDispatcher->notify($event);

        echo $event->getAllContent();
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasContent($name)
    {
        return isset($this->contents[$name]) && !empty($this->contents[$name]);
    }

    /**
     * @param $name
     * @return string
     */
    public function getContent($name)
    {
        if (isset($this->contents[$name])) {
            return $this->contents[$name];
        }

        return '';
    }

    public function setContent($name, $content)
    {
        $this->contents[$name] = $content;
    }

    public function display($name)
    {
        echo $this->getContent($name);
    }

    public function removeContent($name)
    {
        unset($this->contents[$name]);
    }

    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->contents);
    }

    /**
     * @param string $group
     * @return array
     */
    public function getGroupKeys($group)
    {
        return isset($this->groupMap[$group]) ? $this->groupMap[$group] : array();
    }

    public function addGroupKey($group, $key)
    {
        if (!isset($this->groupMap[$group])) {
            $this->groupMap[$group] = array();
        }

        $this->groupMap[$group][] = $key;
    }

    /**
     * @return array
     */
    public function getSystemKeys()
    {
        return $this->getGroupKeys('system');
    }

    public function startJs()
    {
        $this->openJsSlots++;
        ob_start();
        ob_implicit_flush(0);
    }

    public function stopJs()
    {
        $this->openJsSlots--;
        $this->jsContents .= ob_get_clean();
    }

    public function addJsContents($jsContents)
    {
        $this->jsContents .= $jsContents;
    }

    /**
     * @return string
     */
    public function getJsContents()
    {
        return $this->jsContents;
    }
}