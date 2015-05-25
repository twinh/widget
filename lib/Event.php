<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * An event dispatch service
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Event extends Base
{
    /**
     * The array contains the event handlers
     *
     * @var array
     */
    protected $handlers = array();

    /**
     * Trigger an event
     *
     * @param  string $name The name of event or an Event object
     * @param  array $args The arguments pass to the handle
     * @return array
     */
    public function trigger($name, $args = array())
    {
        if (!is_array($args)) {
            $args = array($args);
        }

        // Prepend the service container to the beginning of the arguments
        array_unshift($args, $this->wei);

        $results = array();

        if (isset($this->handlers[$name])) {
            krsort($this->handlers[$name]);
            foreach ($this->handlers[$name] as $handlers) {
                foreach ($handlers as $handler) {
                    $results[] = $result = call_user_func_array($handler, $args);
                    if (false === $result) {
                        break 2;
                    }
                }
            }
        }

        return $results;
    }

    /**
     * Attach a handler to an event
     *
     * @param string|array $name The name of event, or an array that the key is event name and the value is event hanlder
     * @param callback $fn The event handler
     * @param int $priority The event priority
     * @throws \InvalidArgumentException when the second argument is not callable
     * @return $this
     */
    public function on($name, $fn = null, $priority = 0)
    {
        // ( $names )
        if (is_array($name)) {
            foreach ($name as $item => $fn) {
                $this->on($item, $fn);
            }
            return $this;
        }

        // ( $name, $fn, $priority, $data )
        if (!is_callable($fn)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected argument of name callable, "%s" given',
                is_object($fn) ? get_class($fn) : gettype($fn)
            ));
        }

        if (!isset($this->handlers[$name])) {
            $this->handlers[$name] = array();
        }
        $this->handlers[$name][$priority][] = $fn;

        return $this;
    }

    /**
     * Remove event handlers by specified name
     *
     * @param string $name The name of event
     * @return $this
     */
    public function off($name)
    {
        if (isset($this->handlers[$name])) {
            unset($this->handlers[$name]);
        }
        return $this;
    }

    /**
     * Check if has the given name of event handlers
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->handlers[$name]);
    }
}