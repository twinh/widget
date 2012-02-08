<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2012 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 */

/**
 * Session
 *
 * @package     Qwin
 * @subpackage  Widget
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2010-04-18 11:50:10
 */
class Qwin_Session extends Qwin_ArrayWidget
{
    /**
     * Current namespace to store session data
     *
     * @var string
     */
    protected $_namespace;

    /**
     * Whether session started
     *
     * @var string
     */
    protected $_started = false;

    /**
     * @var array           Options
     *
     *      -- namespace    namespace to store session data
     *
     *      -- autoStart    whether auto start session when object construct
     *
     * @see http://php.net/manual/en/session.configuration.php
     */
    public $options = array(
        'namespace'         => 'qwin',
        'autoStart'         => true,
        'cache_limiter'     => 'private_no_expire, must-revalidate',
        'cookie_lifetime'   => 86400,
        'cache_expire'      => 86400,
    );

    public function __construct($options = null)
    {
        // merge options
        $options = (array)$options + $this->options;
        $this->options = &$options;

        // init all options
        $this->option($options);

        if ($options['autoStart']) {
            $this->start();
        }
    }

    /**
     * Get or set options
     *
     * @param mixed $name
     * @param mixed $value
     * @return mixed
     */
    public function option($name = null, $value = null)
    {
        if (2 == func_num_args() && (is_string($name) || is_int($name))) {
            if ('name' == $name || 0 !== strpos($name, '_')) {
                ini_set('session.' . $name, $value);
            }
            // set option
            return parent::option($name, $value);
        }
        return parent::option($name);
    }

    /**
     * Set current namespace
     *
     * @param string $name
     * @return Qwin_Session
     */
    public function setNamespaceOption($name)
    {
        $this->options['namespace'] = $name;
        $this->_namespace = $name;
        return $this;
    }

    /**
     * Start session
     *
     * @return Qwin_Session
     */
    public function start()
    {
        $file = $line = null;
        if (headers_sent($file, $line)) {
            // @codeCoverageIgnoreStart
            return $this->error(sprintf('Unable to start session, output started at %s:%s', $file, $line));
            // @codeCoverageIgnoreEnd
        }

        // session started, ignored
        if (!session_id()) {
            session_start();
            $this->_started = true;
            if (!isset($_SESSION[$this->_namespace])) {
                $_SESSION[$this->_namespace] = array();
            }
            $this->_data = &$_SESSION[$this->_namespace];
        }

        return $this;
    }

    /**
     * Whether session started
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->_started;
    }

    /**
     * Set a session value in current namespace
     *
     * @param string $offset
     * @param mixed $value
     * @return Qwin_Session
     */
    public function set($offset, $value)
    {
        return $this->offsetSet($offset, $value);
    }

    /**
     * Get a session from current namespace
     *
     * @param string $name
     * @return mixed
     */
    public function get($offset)
    {
        return $this->offsetGet($offset);
    }

    /**
     * Clear session in $namespace or current namespace
     *
     * @param string $namespace
     * @return Qwin_Session
     */
    public function clear($namespace = null)
    {
        if ($namespace) {
            $_SESSION[$namespace] = array();
        } else {
            $_SESSION[$this->_namespace] = array();

            // clean up data for cli mode
            $this->_data = array();
        }
        return $this;
    }

    /**
     * Destroy session
     *
     * @return Qwin_Session
     */
    public function destroy()
    {
        if ($this->_started) {
            session_destroy();
        }

        // clean up all data
        $this->_data = array();

        return $this;
    }
}