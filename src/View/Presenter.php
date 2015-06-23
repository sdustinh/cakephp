<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\View;

use Cake\Datasource\EntityInterface;
use Cake\Utility\Inflector;
use Cake\View\PresenterInterface;
use Cake\View\View;

/**
 * A decorator class that separates view logic from the entity.
 */
class Presenter implements PresenterInterface
{
    /**
     * Holds a cached list of methods that exist in the instanced class.
     *
     * @var array
     */
    protected static $_accessors = [];
    
    /**
     * Holds the name of the class for the instance object.
     *
     * @var string
     */
    protected $_className;
    
    /**
     * Entity the presenter is decorating.
     *
     * @var \Cake\Datasource\EntityInterface
     */
    protected $_entity;
    
    /**
     * List of helpers this presenter uses.
     *
     * @var array
     */
    public $helpers = [];
    
    /**
     * Holds all properties and their values for this presenter.
     *
     * @var array
     */
    protected $_properties = [];
    
    /**
     * View the presenter is attached to.
     *
     * @var \Cake\View\View
     */
    public $View;
    
    /**
     * Constructor.
     *
     * @param  EntityInterface $Entity Entity the presenter decorates.
     * @param  \Cake\View\View $View View the presenter is attached to.
     * @return void
     */
    public function __construct(EntityInterface $Entity, View $View)
    {
        $this->_className = get_class($this);
        $this->_entity = $Entity;
        $this->View = $View;
        
        $this->initialize();
    }
    
    /**
     * Magic getter to access properties that have been set in this presenter.
     *
     * @param  string $property Name of the property to access
     * @return mixed
     */
    public function &__get($property)
    {
        $registry = $this->View->helpers();
        
        if (isset($registry->{$property})) {
            $this->{$property} = $registry->{$property};
            return $this->{$property};
        }
        
        return $this->get($property);
    }
    
    /**
     * Returns the string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
    
    /**
     * Returns an array with the requested properties stored in this presenter, indexed by property name.
     *
     * @param  array $properties list of properties to be returned.
     * @return array
     */
    public function extract(array $properties)
    {
        $result = [];
        
        foreach ($properties as $property) {
            $result[$property] = $this->get($property);
        }
        
        return $result;
    }
    
    /**
     * Returns the value of a property by name.
     *
     * @param  string $property Name of the property to get.
     * @return mixed
     */
    public function &get($property)
    {
        $value = $this->_entity->get($property);
        $method = '_get' . Inflector::camelize($property);
        
        if (isset($this->_properties[$property])) {
            $value =& $this->_properties[$property];
        }
        
        if ($this->_methodExists($method)) {
            $result = $this->{$method}($value);
            return $result;
        }
        
        return $value;
    }
    
    /**
     * Empty method to add additional helpers.
     *
     * @return void
     */
    public function initialize()
    {
    }
    
    /**
     * Checks to see if a method exists in this class.
     *
     * @param  string $method The method name to check.
     * @return bool True if the method exists.
     */
    protected function _methodExists($method)
    {
        if (empty(static::$_accessors[$this->_className])) {
            static::$_accessors[$this->_className] = array_flip(get_class_methods($this));
        }
        
        return isset(static::$_accessors[$this->_className][$method]);
    }
    
    /**
     * Returns the properties that will be serialized as JSON
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    
    /**
     * Converts the presenter to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_entity->toArray();
    }
}
