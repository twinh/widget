<?php

/**
 * Widget Framework
 * 
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Is\Rule;

/**
 * DoubleByte
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */

class DoubleByte
{
    public function __invoke($value)
    {
        return (bool) preg_match('/^[^\x00-xff]+$/', $value);
    }
}
