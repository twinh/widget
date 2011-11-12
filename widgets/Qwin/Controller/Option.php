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
 * Option
 * 
 * @namespace   Qwin
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @author      Twin Huang <twinh@yahoo.cn>
 * @since       2011-11-10 14:14:04
 */
class Qwin_Controller_Option extends ArrayObject
{
    protected $_controller;
    
    /**
     * 初始化类
     *
     * @param array $input 数据
     */
    public function  __construct($input = array())
    {
        parent::__construct($input, self::ARRAY_AS_PROPS);
    }
    
    public function setController($controller)
    {
        $this->_controller = $controller;
        return $this;
    }
    
    public function getController()
    {
        return $this->_controller;
    }
    
    /**
     * 获取指定键名的元数据值，元数据不存在时将抛出异常
     *
     * @param string $index 键名
     * @return mixed
     */
    public function offsetGet($index)
    {
        if (parent::offsetExists($index)) {
            return parent::offsetGet($index);
        } elseif ($value = $this->_offsetGetByFile($index)) {
            return $value;
        }
        throw new Qwin_Exception('Undefined index "' . $index . '"');
    }
    
        /**
     * 根据键名加载元数据文件，元数据不存在时返回false
     *
     * @param string $index 键名
     * @return mixed
     */
    protected function _offsetGetByFile($index, $driver = null)
    {
        $dir = $this->_controller->getControllerDir();
        $file = $dir . '/options/' . $index . '.php';
        if (is_file($file)) {
            $data = require $file;
            $this->exchangeArray(array($index => $data) + $this->getArrayCopy());
            return $data;
        }
        return false;
    }
}

