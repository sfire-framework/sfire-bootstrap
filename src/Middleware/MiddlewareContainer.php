<?php 
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Middleware;

use sFire\Bootstrap\Exception\InvalidArgumentException;


/**
 * Class MiddlewareContainer
 * @package sFire\Bootstrap
 */
class MiddlewareContainer {


    /**
     * Contains all the middleware before executing of the controller
     * @var array
     */
    private static array $before = [];


    /**
     * Contains all the middleware after executing of the controller
     * @var array
     */
    private static array $after = [];


    /**
     * Contains the current mode (before or after)
     * @var string
     */
    private static string $mode = 'before';


    /**
     * Contains all the found namespaces with class instances
     * @var array
     */
    private static array $namespaces = [];


    /**
     * Contains all the route variables to inject into the middleware
     * @var array
     */
    private static array $matches = [];


    /**
     * Keeps count of all the middleware that has been executed
     * @var array
     */
    private static array $counter = [

        'before' => -1,
        'after' => -1
    ];


    /**
     * Execute the next middleware if available
     * @return void
     */
    public static function next(): void {

        switch(static::$mode) {

            case 'before':

                static::$counter['before']++;

                if(true === isset(static::$before[static::$counter['before']])) {

                    $middleware = static::$before[static::$counter['before']];

                    //Execute method if middleware supports it
                    call_user_func_array([$middleware, 'before'], static::$matches);
                }

                break;

            case 'after':

                static::$counter['after']++;

                if(true === isset(static::$after[static::$counter['after']])) {

                    $middleware = static::$after[static::$counter['after']];

                    //Execute method if middleware supports it
                    call_user_func_array([$middleware, 'after'], static::$matches);
                }

                break;
        }
    }


    /**
     * Reset the counters
     * @return void
     */
    public function reset(): void {

        static::$counter['before'] = -1;
        static::$counter['after'] = -1;
    }


    /**
     * Sets the route variables to inject into the next middleware that needs to be executed
     * @param array $matches
     * @return void
     */
    public function matches(array $matches): void {
        static::$matches = $matches;
    }


    /**
     * Add a new middleware class with a type (before or after), so this class with a before or after method will execute before of after the controller
     * @param string $namespace
     * @param string $type
     * @return void
     * @throws InvalidArgumentException
     */
    public function add(string $namespace, string $type): void {

        if(false === in_array($type, ['before', 'after'])) {
            throw new InvalidArgumentException(sprintf('Argument 2 passed to %s() must be either "before" or "after", "%s" given', __METHOD__, gettype($type)));
        }

        //Check if middleware class exists
        if(false === class_exists($namespace)) {
            throw new InvalidArgumentException(sprintf('Middleware "%s" does not exists', $namespace));
        }

        //Check if before or after method exists in middleware
        if(false === is_callable([$namespace, $type])) {
            throw new InvalidArgumentException(sprintf('Method "%s()" does not exists for middleware "%s"', $type, $namespace));
        }

        static::$namespaces[$namespace] ??= new $namespace;

        switch($type) {

            case 'before': static::$before[] = static::$namespaces[$namespace]; break;
            case 'after': static::$after[] = static::$namespaces[$namespace]; break;
        }
    }


    /**
     * Check if there is more middleware to execute
     * @return bool
     */
    public function isEmpty(): bool {

        switch(static::$mode) {

            case 'before': return static::$counter[static::$mode] === count(static::$before); break;
            case 'after': return static::$counter[static::$mode] === count(static::$after); break;
        }

        return true;
    }


    /**
     * Set the current mode
     * @param string $mode The mode (before or after)
     * @throws InvalidArgumentException
     */
    public function mode(string $mode): void {

        if(false === in_array($mode, ['before', 'after'])) {
            throw new InvalidArgumentException(sprintf('Argument 1 passed to %s() must be either "before" or "after", "%s" given', __METHOD__, gettype($mode)));
        }

        static::$mode = $mode;
    }
}