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

use sFire\Config\App;
use sFire\Routing\Forward;
use sFire\Bootstrap\Module\Module;
use sFire\Routing\Router as RouterObject;
use sFire\Bootstrap\Mvc\Mvc;


/**
 * Class Router
 * @package sFire\Bootstrap
 */
class Router {


    /**
     * Contains instance of Cookie
     * @var null|Router
     */
    private static ?self $instance = null;


    /**
     * Returns an instance of self
     * @return self
     */
    public static function getInstance(): self {

        if(null === static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }


    /**
     * Convert a route based on a given identifier to a URL
     * @param string $identifier The unique identifier of a existing route
     * @param null|array $variables [optional] Variables to be inserted into the route URL
     * @param null|string $domain [optional] A single domain/host that will be prepended to the route URL
     * @return string
     */
    public function routeToUrl(string $identifier, ?array $variables = [], string $domain = null): string {
        return RouterObject :: url($identifier, $variables, $domain);
    }


    /**
     * Returns a matching route based on a given url
     * @param string $url
     * @return null|\sFire\Routing\RouteEntity
     */
    public function urlToRoute(string $url): \sFire\Routing\RouteEntity {
        return RouterObject :: urlToRoute($url);
    }


    /**
     * Returns a route based on a route identifier. If no identifier is provider, the current route will be returned
     * @param null|string $identifier [optional] The identifier of a route
     * @return RouteEntity
     */
    public function getRoute(string $identifier = null): RouteEntity {
        return Module :: getRoute($identifier);
    }


    /**
     * Returns all the routes as an array of RouteEntities
     * @return RouteEntity[]
     */
    public function getRoutes(): array {
        return RouterObject :: getRoutes();
    }


    /**
     * Check if a Route exists by identifier with optional domain
     * @param string $identifier
     * @param null|string $domain
     * @return bool
     */
    public static function routeExists(string $identifier, string $domain = null): bool {
        return RouterObject::routeExists($identifier, $domain);
    }


    /**
     * Forward (HTTP 302 Redirect) client to route by given identifier
     * @param string $identifier
     * @return Forward
     */
    public function forward(string $identifier): Forward {
        return $this -> getRoute($identifier) -> forward();
    }


    /**
     * Execute the controller and method of a route by giving a route identifier
     * @param string $identifier The Route identifier
     * @return mixed
     */
    public function redirect(string $identifier) {

        $route      = $this -> getRoute($identifier);
        $controller = Mvc::getMvcClass('controller', $route -> getModule(), $route -> getController());
        $controller = new $controller();

        //Execute action
        $action = App :: getInstance() -> get('prefix.action') . $route -> getAction();

        //Execute main controller action
        return call_user_func_array([$controller, $action], []);
    }
}