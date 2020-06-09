<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Module;

use sFire\DataControl\Translators\StringTranslator;
use sFire\Bootstrap\Exception\RuntimeException;
use sFire\Bootstrap\Mvc\Helpers\RouteEntity;
use sFire\Routing\Router;


/**
 * Class Module
 * @package sFire\Bootstrap
 */
class Module {


    /**
     * Contains all the parsed route entities
     * @var array
     */
    static array $routeEntities = [];


    /**
     * Contains all the config cache
     * @var array
     */
    static array $configCache = [];


    /**
     * Contains instance of Request
     * @var null|Module
     */
    private static ?self $instance = null;


    /**
     * Contains the current module name
     * @var null|string
     */
    private static ?string $moduleName = null;


    /**
     * Returns instance of Module
     * @return self
     */
    public static function getInstance(): self {

        if(null === static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }


    /**
     * Set the current module name
     * @param null|string $name
     * return void
     */
    public static function setModuleName(?string $name): void {
        static::$moduleName = $name;
    }


    /**
     * Returns the current module name
     * @return null|string
     */
    public static function getModuleName(): ?string {
        return static::$moduleName;
    }


    /**
     * Config helper method to retrieve configuration settings
     * @param null|string $property
     * @param null $default
     * @return mixed
     * @throws RuntimeException
     */
    public static function getConfig(string $property = null, $default = null) {

        $file = static :: getPaths() -> getConfig() . 'config.php';
        $hash = md5($file);

        if(false === isset(static::$configCache[$hash])) {

            if(false === is_readable($file)) {
                throw new RuntimeException(sprintf('Cannot read module config file. File "%s" does not exists or is not readable.', $file));
            }

            static::$configCache[$hash] = require_once($file);
        }

        $config = static::$configCache[$hash];

        if(false === is_array($config)) {
            throw new RuntimeException(sprintf('Config loaded with "%s" is not an array', $file));
        }

        if(null === $property) {
            return $config;
        }

        $translator = new StringTranslator($config);
        $value = $translator -> get($property);

        return $value !== null ? $value : $default;
    }


    /**
     * Returns an instance of Path for retrieving module specific folders like config, controllers, views, .etc.
     * @return Path
     */
    public static function getPaths(): Path {
        return Path :: getInstance();
    }


    /**
     * Returns a route based on a route identifier. If no identifier is provider, the current route will be returned
     * @param null|string $identifier [optional] The identifier of a route
     * @return RouteEntity
     */
    public static function getRoute(string $identifier = null): RouteEntity {

        $route = Router :: getRoute($identifier);

        if(true === isset(static::$routeEntities[$route -> getIdentifier()])) {
            return static::$routeEntities[$route -> getIdentifier()];
        }

        static::$routeEntities[$route -> getIdentifier()] = new RouteEntity($route);

        return static::$routeEntities[$route -> getIdentifier()];
    }
}