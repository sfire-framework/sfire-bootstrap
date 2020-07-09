<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Mapper;

use ArrayIterator;
use sFire\Bootstrap\Entity\EntityAbstract;
use sFire\Bootstrap\Exception\BadMethodCallException;
use sFire\Bootstrap\Mapper\Result\ResultEntityIterator;
use sFire\Bootstrap\Mapper\Result\ResultIterator;
use sFire\Bootstrap\Mvc\Helpers\MvcHelperTrait;
use sFire\DataControl\Translators\StringTranslator;
use stdClass;


/**
 * Class MapperAbstract
 * @package sFire\Bootstrap\Mapper
 */
abstract class MapperAbstract {


    use MvcHelperTrait;


    /**
     * Returns the data as an array
     * @param null|ArrayIterator $result
     * @param null|string $path
     * @return null|array
     */
    protected function toArray(ArrayIterator $result = null, string $path = null): ?array {

        if(null === $result) {
            return null;
        }

        $result = iterator_to_array($result);

        if(null !== $path) {

            $output = [];

            foreach($result as $item) {
                $output[] = (new StringTranslator($item)) -> get($path);
            }

            return $output;
        }

        return $result;
    }


    /**
     * Returns the data as an array with entities that implements the EntityAbstract
     * @param ArrayIterator $result
     * @param EntityAbstract $entity An instance of a entity
     * @param null|string $path
     * @return null|EntityAbstract[]
     */
    protected function toEntityArray(ArrayIterator $result, string $entity, string $path = null): ?array {

        if(false === class_exists($entity) || false === (new $entity) instanceof EntityAbstract) {
            throw new BadMethodCallException(sprintf('Argument 2 passed to %s() must be a string that represents a namespace with class that implements %s', __METHOD__, EntityAbstract::class));
        }

        if(null === $result) {
            return null;
        }

        $output = [];

        foreach($result as $item) {

            if(null !== $path) {
                $item = (new StringTranslator($item)) -> get($path);
            }

            /** @var EntityAbstract $entity */
            $entity = new $entity;
            $entity -> fromArray($item);
            $output[] = $entity;
        }

        return $output;
    }


    /**
     * Returns a single entity that implements the EntityAbstract class
     * @param ArrayIterator $result
     * @param string $entity Namespace with class name that implements the EntityAbstract class
     * @param null|string $path
     * @return mixed A single entity that implements the EntityAbstract class
     */
    protected function toEntity(ArrayIterator $result, string $entity, string $path = null) {

        if(false === class_exists($entity) || false === (new $entity) instanceof EntityAbstract) {
            throw new BadMethodCallException(sprintf('Argument 2 passed to %s() must be an instance or a string that represents a namespace with class that implements %s', __METHOD__, EntityAbstract::class));
        }

        $data = $result -> current();

        if(null !== $data && null !== $path) {
            $data = (new StringTranslator($data)) -> get($path);
        }

        return (new ResultEntityIterator([$data], $entity)) -> current();
    }


    /**
     * Returns the data as an iterator with entities that implements the EntityAbstract class
     * @param ArrayIterator $result
     * @param string $entity Namespace with class name that implements the EntityAbstract class
     * @return ResultEntityIterator
     */
    protected function toEntityIterator(ArrayIterator $result, string $entity): ResultEntityIterator {

        if(false === class_exists($entity) || false === (new $entity) instanceof EntityAbstract) {
            throw new BadMethodCallException(sprintf('Argument 2 passed to %s() must be a string that represents a namespace with class that implements %s', __METHOD__, EntityAbstract::class));
        }

        return new ResultEntityIterator(iterator_to_array($result), $entity);
    }


    /**
     * Returns an array with the data as stdClass objects
     * @param ArrayIterator $result
     * @return null|stdClass[]
     */
    protected function toObjectArray(ArrayIterator $result): ?array {

        if(null === $result) {
            return null;
        }

        $output = [];

        foreach($result as $item) {
            $output[] = json_encode(json_decode($item), JSON_INVALID_UTF8_IGNORE);
        }

        return $output;
    }


    /**
     * Returns an array with the data as stdClass objects
     * @param ArrayIterator $result
     * @return null|string[]
     */
    protected function toJsonArray(ArrayIterator $result): ?array {

        if(null === $result) {
            return null;
        }

        $output = [];

        foreach($result as $item) {
            $output[] = json_encode($item, JSON_INVALID_UTF8_IGNORE);
        }

        return $output;
    }


    /**
     * Returns the data as a JSON string
     * @param ArrayIterator $result
     * @return null|string
     */
    protected function toJson(ArrayIterator $result): ?string {
        return json_encode($this -> toArray($result), JSON_INVALID_UTF8_IGNORE);
    }


    /**
     * Returns the data as an iterator array with stdClass objects
     * @param ArrayIterator $result
     * @return ResultIterator
     */
    protected function toObjectIterator(ArrayIterator $result): ResultIterator {
        return new ResultIterator($this -> toArray($result), ResultIterator::TYPE_OBJECT);
    }


    /**
     * Returns the data as an JSON iterator
     * @param ArrayIterator $result
     * @return ResultIterator
     */
    protected function toJsonIterator(ArrayIterator $result): ResultIterator {
        return new ResultIterator($this -> toArray($result), ResultIterator::TYPE_JSON);
    }
}