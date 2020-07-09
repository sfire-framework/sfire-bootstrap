<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Entity;

use DateTime;
use Exception;
use ReflectionClass;
use sFire\DataControl\TypeString;
use sFire\Db\Exception\BadMethodCallException;
use sFire\Bootstrap\Gateway\GatewayAbstract;
use sFire\Bootstrap\Mvc\Helpers\MvcHelperTrait;
use stdClass;


/**
 * Class EntityAbstract
 * @package sFire\Bootstrap
 */
abstract class EntityAbstract {


    use MvcHelperTrait;


    /**
     * Contains an instance of a class that inherits the GatewayAbstract class
     * @var null|GatewayAbstract
     */
    private ?GatewayAbstract $gateway = null;


    /**
     * Contains all the properties
     * @var array
     */
    protected array $_properties = [];


    /**
     * Catch all method for setting and getting properties
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws BadMethodCallException
     * @throws Exception
     */
    public function __call($method, $parameters) {

        foreach($this -> _properties as $property) {

            //Getters
            if($property -> getter === $method) {

                $value = $this -> getValueFromProperty($property);

                if(null === $value) {
                    return null;
                }

                switch($property -> type) {

                    case 'string' : return (string) $value;
                    case 'int'    : return (int) $value;
                    case 'float'  : return (float) $value;
                    case 'bool'   : return (bool) $value;
                    case 'date'   : return new DateTime($value);
                    case 'json'   : return json_decode($value, true);
                }

                return $value;
            }

            //Setters
            if($property -> setter === $method) {

                $property -> value = $parameters[0];
                return $this;
            }
        }

        throw new BadMethodCallException(sprintf('Call to undefined method %s::%s()', (new ReflectionClass($this)) -> getName(), $method));
    }


    /**
     * Returns the current gateway
     * @return GatewayAbstract
     */
    protected function getGateway(): GatewayAbstract {
        return $this -> gateway;
    }


    /**
     * Sets a new gateway
     * @param GatewayAbstract $gateway
     * @return void
     */
    protected function setGateway(GatewayAbstract $gateway): void {
        $this -> gateway = $gateway;
    }



    /**
     * Creates new entity. If entity already exists, if will be updated.
     * @return bool
     */
    abstract public function save(): bool;


    /**
     * Refreshes the current entity based on identifier(s) and value(s)
     * @return bool
     */
    abstract public function reload(): bool;


    /**
     * Deletes the current entity based on identifier(s)
     * @return bool
     */
    abstract public function delete(): bool;


    /**
     * Add a new property
     * @param string $propertyName
     * @return Property
     */
    final public function property(string $propertyName): Property {

        $property = new Property($propertyName);
        $property -> getter('get' . TypeString :: toPascalCase($propertyName));
        $property -> setter('set' . TypeString :: toPascalCase($propertyName));

        $this -> _properties[$propertyName] = $property;

        return $property;
    }


    /**
     * Load data from array
     * @param array $data
     * @return void
     */
    final public function fromArray(array $data): void {

        foreach($this -> _properties as $property) {

            if(true === isset($data[$property -> name])) {
                $this -> {$property -> setter}($data[$property -> name]);
            }
        }
    }


    /**
     * Convert entity to array
     * @return array
     */
    final public function toArray(): array {

        $data = [];

        foreach($this -> _properties as $property) {

            $value = $this -> getValueFromProperty($property);

            if('json' === $property -> type && true === is_string($value)) {
                $value = json_decode($value, true);
            }

            $data[$property -> name] = $value;
        }

        return $data;
    }


    /**
     * Convert entity to stdClass
     * @return stdClass
     */
    final public function toObject(): stdClass {
        return (object) $this -> toArray();
    }


    /**
     * Load data from stdClass
     * @param stdClass $object
     * @return void
     */
    final public function fromObject(stdClass $object): void {
        $this -> fromArray((array) $object);
    }


    /**
     * Convert entity to JSON string
     * @return string
     */
    final public function toJson(): string {
        return json_encode($this -> toArray(), JSON_INVALID_UTF8_IGNORE);
    }


    /**
     * Clears all data stored in entity except the identifier(s)
     * @return void
     */
    final public function clear(): void {

        foreach($this -> _properties as $property) {
            $this -> {$property -> setter}(null);
        }
    }


    /**
     * Will be triggered before saving entity
     */
    protected function beforeSave() {
    }


    /**
     * Will be triggered after saving entity
     */
    protected function afterSave() {
    }


    /**
     * Will be triggered before deleting entity
     */
    protected function beforeDelete() {
    }


    /**
     * Will be triggered after deleting entity
     */
    protected function afterDelete() {
    }


    /**
     * Will be triggered before the entity is reloaded
     */
    protected function beforeReload() {
    }


    /**
     * Will be triggered after the entity is reloaded
     */
    protected function afterReload() {
    }


    /**
     * Retrieve the value of the property. Check if the value has been set by the magick setter or has been set with a defined setter. If non of those, return the default value.
     * @param Property $property
     * @return mixed
     */
    final protected function getValueFromProperty(Property $property) {

        return $property -> value
            ?? $this -> {$property -> name}
            ?? $this -> {TypeString::toCamelCase($property -> name)}
            ?? $this -> {TypeString::toPascalCase($property -> name)}
            ?? $this -> {TypeString::toSnakeCase($property -> name)}
            ?? (method_exists($this, $property -> getter) ? $this -> {$property -> getter}() : null)
            ?? $property -> defaultValue
            ?? null;
    }
}