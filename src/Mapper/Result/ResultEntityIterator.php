<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Mapper\Result;

use ArrayIterator;
use ReflectionClass;
use ReflectionException;
use sFire\Bootstrap\Entity\EntityAbstract;
use sFire\Bootstrap\Exception\BadMethodCallException;


/**
 * Class ResultIterator
 * @package sFire\Bootstrap
 */
class ResultEntityIterator extends ArrayIterator {


    /**
     * Contains the namespace and classname of the entity
     * @var null|string
     */
    private ?string $entityClassName = null;


    /**
     * Constructor
     * @param array $data
     * @param string $entity Namespace with class name that implements the EntityAbstract class
     * @throws BadMethodCallException
     * @throws ReflectionException
     */
    public function __construct(array $data, string $entity) {

        if(false === class_exists($entity) || false === (new $entity) instanceof EntityAbstract) {
            throw new BadMethodCallException(sprintf('Argument 2 passed to %s() must be a string that represents a namespace with class that implements %s', __METHOD__, EntityAbstract::class));
        }

        parent :: __construct($data);

        $reflection = new ReflectionClass($entity);
        $this -> entityClassName = $reflection -> getName();
    }


    /**
     * Return current array entry as an entity
     * @return mixed
     */
    public function current() {

        $current = parent::current();

        if(false === is_array($current)) {
            return $current;
        }

        /** @var EntityAbstract $entity */
        $entity = new $this -> entityClassName();
        $entity -> fromArray($current);

        return $entity;
    }
}