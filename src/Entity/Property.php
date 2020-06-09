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


/**
 * Class Property
 * @package sFire\Bootstrap
 */
class Property {


    /**
     * Contains the name of the property
     * @var null|string
     */
    public ?string $name = null;


    /**
     * Contains the name of the getter method
     * @var null|string
     */
    public ?string $getter = null;


    /**
     * Contains the name of the setter method
     * @var null|string
     */
    public ?string $setter = null;


    /**
     * Contains the property type (i.e. int, bool, etc.)
     * @var null|string
     */
    public ?string $type = null;


    /**
     * Contains if the property is a primary property (key)
     * @var bool
     */
    public bool $isPrimary = false;


    /**
     * Contains if the property is a generated (column) property
     * @var bool
     */
    public bool $isGenerated = false;


    /**
     * Contains the default value of the property
     * @var mixed
     */
    public $defaultValue = null;


    /**
     * Contains if a column is defined as unique
     * @var null|bool
     */
    public bool $unique = false;


    /**
     * Constructor
     * @param string $propertyName
     */
    public function __construct(string $propertyName) {
        $this -> name = $propertyName;
    }


    /**
     * Defines the type of the current property as a string
     * @return self
     */
    public function string(): self {

        $this -> type = 'string';
        return $this;
    }


    /**
     * Defines the type of the current property as an integer number
     * @return self
     */
    public function int(): self {

        $this -> type = 'int';
        return $this;
    }


    /**
     * Defines the type of the current property as a boolean
     * @return self
     */
    public function bool(): self {

        $this -> type = 'bool';
        return $this;
    }


    /**
     * Defines the type of the current property as a date(time)
     * @return self
     */
    public function date(): self {

        $this -> type = 'date';
        return $this;
    }


    /**
     * Defines the type of the current property as a float
     * @return self
     */
    public function float(): self {

        $this -> type = 'float';
        return $this;
    }


    /**
     * Defines the type of the current property as a JSON string
     * @return self
     */
    public function json(): self {

        $this -> type = 'json';
        return $this;
    }


    /**
     * Defines a getter method for the property
     * @param string $methodName
     * @return self
     */
    public function getter(string $methodName): self {

        $this -> getter = $methodName;
        return $this;
    }


    /**
     * Defines a setter method for the property
     * @param string $methodName
     * @return self
     */
    public function setter(string $methodName): self {

        $this -> setter = $methodName;
        return $this;
    }


    /**
     * Defines a default value for the property
     * @param mixed $defaultValue
     * @return self
     */
    public function default($defaultValue): self {

        $this -> defaultValue = $defaultValue;
        return $this;
    }


    /**
     * Defines the property as a primary key
     * @param bool $isPrimary
     * @return self
     */
    public function primary(bool $isPrimary = true): self {

        $this -> isPrimary = $isPrimary;
        return $this;
    }


    /**
     * Defines the property as a unique key
     * @param bool $isUnique
     * @return self
     */
    public function unique(bool $isUnique = true): self {

        $this -> unique = $isUnique;
        return $this;
    }


    /**
     * Defines the property as a generated property i.e. autoincrement key, generated column, current timestamp date column, etc.
     * @param bool $isGenerated
     * @return self
     */
    public function generated(bool $isGenerated = true): self {

        $this -> isGenerated = $isGenerated;
        return $this;
    }
}