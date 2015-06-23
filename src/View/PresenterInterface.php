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

use JsonSerializable;

/**
 * Describes the methods that any class representing a presenter should comply with.
 */
interface PresenterInterface extends JsonSerializable
{
    /**
     * Returns an array with the requested properties
     * stored in this presenter, indexed by property name
     *
     * @param  array $properties List of properties to be returned.
     * @return array
     */
    public function extract(array $properties);
    
    /**
     * Returns the value of a property by name.
     *
     * @param  string $property The name of the property to retrieve.
     * @return mixed
     */
    public function &get($property);
    
    /**
     * Returns an array with all the properties that have been set
     * to this presenter.
     *
     * @return array
     */
    public function toArray();
}
