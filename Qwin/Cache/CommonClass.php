<?php
/**
 * CommonClass 的名称
 *
 * CommonClass 的简要介绍
 *
 * Copyright (c) 2009 Twin. All rights reserved.
 * 
 * LICENSE:
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Twin Huang <twinh@yahoo.cn>
 * @copyright Twin Huang
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   2010-02-19 17:12 utf-8 中文
 * @since     2010-02-19 17:12 utf-8 中文
 * @todo      Exception
 */

/**
 * @see Qwin_Cache
 */
require_once 'Qwin/Cache.php';

class Qwin_Cache_CommonClass extends Qwin_Cache
{
    private $_path;
    private $_table;
    
    public function __construct()
    {
        $this->_table = 'common_class';
        $this->_path = ROOT_PATH . '/Cache/php/CommonClass/';
        $this->_default_lang = 'en';
    }
    
    /**
     * 配置缓存的路径
     */
    public function setPath($path)
    {
        $this->_path = $path;
        return $this;
    }
    
    public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }
    
    /**
     * 加载缓存数组
     *
     * @param string $var_name 类型名称
     * @param string $lang 语言代号
     * @return array
     * @todo 文件未找到时,加载默认语言的缓存文件,否则抛出错误
     */
    public function getCache($var_name, $lang = 'en')
    {
        $path = $this->_path . $lang . DS . $var_name . '.php';
        if(file_exists($path))
        {
            return require $path;
        } else {
            $path = $this->_path . 'en' . DS . $var_name . '.php';
            if(file_exists($path))
            {
                return require $path;
            }
        }
    }
    
    /**
     * 生成缓存
     */
    public function setCache($code)
    {
        if(strlen($code) < 4)
        {
            return array();
        }
        $controller = Qwin_Class::run('-c');
        $query = $controller->meta->getQuery($controller->__query);
        // 数据库取出对应分类
        $code = substr($code, 0, 4);
        $sql_data = $query->where('LEFT(code, 4) = ?', $code)
            ->orderBy('order ASC')
            ->execute()
            ->toArray();
        if(0 == count($sql_data))
        {
            return array();
        }
        
        $cache_data = array();
        foreach($sql_data as $key => &$val)
        {
            if(substr($val['code'], 4) == '000')
            {
                $var_name = $val['var_name'];
                continue;
            }
            $val['value'] = @unserialize($val['value']);
            // 多语言处理
            foreach($val['value'] as $lang => $lang_val)
            {
                $cache_data[$lang][$val['code']] = $lang_val;
            }
        }
        //$data = $this->_getDataByCode($code);
        $cache_data = $this->_adjustData($cache_data);
        $this->_writeCache($cache_data, $var_name);
    }
    
    /**
     * 将缓存写入文件中
     *
     */
    private function _writeCache($data, $var_name)
    {
        foreach($data as $lang => $val)
        {
            $path = $this->_path . $lang;
            if(!is_dir($path))
            {
                mkdir($path);
            }
            parent::writeArr($val, $path . DS . $var_name . '.php');
         }
    }
    
    /**
     * 根据提供的代码,获取数据
     *
     */
    /*private function _getDataByCode($code)
    {
        
    }*/
    
    /**
     * 对不完善的语言, 使用默认语言补全
     */
    private function _adjustData($cache_data)
    {
        foreach($cache_data as $lang => &$row)
        {
            if($this->_default_lang == $lang)
            {
                continue;
            }
            // X $row = $row + $cache_data[$this->_default_lang];
            // 创建一个新的数组,可以保证排序与默认数组一致
            $new_row = array();
            foreach($cache_data[$this->_default_lang] as $code => $value)
            {
                if(!isset($row[$code]) || '' == $row[$code])
                {
                    $new_row[$code] = $value;
                } else {
                    $new_row[$code] = $row[$code];
                }
                $row[$code] = $new_row[$code];
            }
        }
        return $cache_data;
    }
}