<?php
/**
 * Qwin Framework
 *
 * Copyright (c) 2008-2011 Twin Huang. All rights reserved.
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
 * JQuery
 * 
 * @package     Qwin
 * @subpackage  Application
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-01-29 16:42:43
 */
class Qwin_JQuery extends Qwin_Widget
{
    public function __construct($source = null)
    {
        parent::__construct($source);
        $this->_dir = dirname(__FILE__) . '/JQuery/';
    }
    
    public function getDir()
    {
        return $this->_dir;
    }
    
    public function call()
    {
        return $this;
    }

    /**
     * 加载核心文件
     *
     * @return string
     */
    public function loadCore()
    {
        $file = $this->_dir . 'jquery.js';
        return $file;
    }

    /**
     * 加载UI文件
     *
     * @param string $name 名称
     * @return array
     */
    public function loadUi($name)
    {
        return array(
            'js' => $this->_dir . 'ui/jquery.ui.' . $name . '.min.js',
            'css' => $this->_dir . 'ui/jquery.ui.' . $name . '.css',
        );
    }

    /**
     * 加载效果
     *
     * @param string $name 名称
     * @return string
     */
    public function loadEffect($name)
    {
        return $this->_dir . 'effects/jquery.effects.' . $name . '.min.js';
    }

    /**
     * 加载插件
     *
     * @param string $name 名称
     * @param string $type 类型,如min,pack
     * @return array
     */
    public function loadPlugin($name, $type = null)
    {
        $js = $this->_dir . 'plugins/' . $name . '/jquery.' . $name;
        $js .= $type ? '.' . $type : null;
        $js .= '.js';
        return array(
            'js' => $js,
            'css' => $this->_dir . 'plugins/' . $name . '/jquery.' . $name . '.css',
        );
    }
    
    public function loadTheme($name)
    {
        return $this->_dir . 'themes/' . $name . '/jquery.ui.theme.css';
    }
}
