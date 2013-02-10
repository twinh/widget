<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget\Validator;

use Widget\WidgetProvider;

abstract class AbstractRule extends WidgetProvider implements RuleInterface
{
    /**
     * The invalid message
     * 
     * @var string
     */
    protected $message = '%name% is not valid';
    
    /**
     * The message name
     * 
     * @var string
     */
    protected $name = 'This value';
    
    /**
     * The error definition
     * 
     * @var array
     */
    protected $errors = array();
    
    /**
     * {@inheritdoc}
     */
    public function isValid($input)
    {
        return $this->__invoke($input);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        $messages = array();
        foreach ($this->errors as $name => $vars) {
            if (!$vars) {
                $messages[$name] = $this->{$name . 'Message'};
            } else {
                $messages[$name] = $this->getErrorMessage($name, $this->{$name . 'Message'});
            }
            
        }
        return $messages;
    }

    /**
     * Returns the invalid message
     * 
     * @todo remove
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the invalid message
     * 
     * @todo remove
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
    
    /**
     * Returns message name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets message name
     * 
     * @param string $name
     * @return \Widget\Validator\AbstractRule
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Returns error message
     * 
     * @param string $name
     * @param string $message
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getErrorMessage($message, $vars = array())
    {
        if (!$message) {
            $message = $this->message;
        }
        
        if ($vars) {
            $keys = array_keys($vars);
            array_walk($keys, function(&$key) {
                $key = '%' . $key  . '%';
            });
            return str_replace($keys, $vars, $message);
        } elseif (preg_match_all('/\%(.+?)\%/', $message, $matches)) {
            $keys = array();
            $values = array();
            foreach ($matches[1] as $match) {
                if (!isset($this->$match)) {
                    throw new \UnexpectedValueException('Unkonwn property ' . $match);
                }
                $value = $this->$match;
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }
                $keys[] = '%' . $match . '%';
                $values[] = $value;
            }
            return str_replace($keys, $values, $message);
        } else {
            return $message;
        }
    }
    
    /**
     * Returns error definition
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Adds error definition
     * 
     * @param string $name
     * @param array $vars
     */
    protected function addError($name, $vars = array())
    {
        $this->errors[$name] = array($this->{$name . 'Message'}, $vars);
    }
}
