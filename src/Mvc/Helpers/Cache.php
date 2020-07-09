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


class Cache {


    /**
     * Contains all CacheObjects
     * @var CacheObject[]
     */
    private static array $cacheObjects = [];


    /**
     * Contains instance of self
     * @var null|self
     */
    private static ?self $instance = null;


    /**
     * Returns instance of self
     * @return self
     */
    public static function getInstance(): self {

        if(null === static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }


    /**
     * Retrieves cache based on given key. If cache does not exists, the cache will be created by calling the given method.
     * @param string $key A unique key for the cache for storing and retrieval
     * @param callable $method A function (closure) that will be executed if the cache is not found. The result will be stored and returned.
     * @param array $arguments [optional] Cache is based on a unique key but can be combined with the arguments as cache key
     * @return mixed
     */
    public function get(string $key, callable $method, array $arguments = []) {

        $cacheObject = static::$cacheObjects[$key] ?? static::$cacheObjects[$key] = new CacheObject();

        if(false === $cacheObject -> equals($arguments)) {

            $cacheObject -> setArguments($arguments);
            $cacheObject -> setValue($method());
        }

        return $cacheObject -> getValue();
    }


    /**
     * Invalidates cache based on a given key
     * @param string $key A unique key for the cache
     * @return bool
     */
    public function invalidate(string $key): bool {

        $result = array_key_exists($key, static::$cache);

        if(true === $result) {
            unset(static::$cache[$key]);
        }

        return $result;
    }
}