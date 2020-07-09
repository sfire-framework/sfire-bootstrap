<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Mvc\Helpers;


/**
 * Class CacheObject
 * @package sFire\Bootstrap
 */
class CacheObject {


    /**
     * Contains the value of the cache
     * @var mixed
     */
    private $value = null;


    /**
     * Contains a serialized string of the arguments
     * @var null|string
     */
    private ?string $arguments = null;


    /**
     * Sets the arguments
     * @param $arguments
     * @return void
     */
    public function setArguments(array $arguments): void {
        $this -> arguments = serialize($arguments);
    }


    /**
     * Returns if the current arguments equals (hit) the given arguments
     * @param array $arguments
     * @return bool
     */
    public function equals(array $arguments): bool {
        return $this -> arguments === serialize($arguments);
    }


    /**
     * Set the value of the cache
     * @param mixed $value
     * @return void
     */
    public function setValue($value): void {
        $this -> value = $value;
    }


    /**
     * Returns the value of the cache
     * @return mixed
     */
    public function getValue() {
        return $this -> value;
    }
}