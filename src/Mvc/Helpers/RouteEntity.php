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

use sFire\Routing\Forward;
use sFire\Routing\RouteEntity as RouteEntityObject;


/**
 * Class RouteEntity
 * @package sFire\Bootstrap
 */
class RouteEntity {


    /**
     * Contains an instance of RouteEntityObject
     * @var null|RouteEntityObject
     */
    private RouteEntityObject $routeEntity;


    /**
     * Constructor
     * @param RouteEntityObject $routeEntity
     */
    public function __construct(RouteEntityObject $routeEntity) {
        $this -> routeEntity = $routeEntity;
    }


    /**
     * Returns the route action
     * @return null|string
     */
    public function getAction(): ?string {
        return $this -> routeEntity -> getAction();
    }


    /**
     * Returns an array with assigned route variables
     * @return array
     */
    public function getAssign(): ?array {
        return $this -> routeEntity -> getAssign();
    }


    /**
     * Returns the route controller
     * @return null|string
     */
    public function getController(): ?string {
        return $this -> routeEntity -> getController();
    }


    /**
     * Returns a array of domains where the route listen to
     * @return null|array
     */
    public function getDomains(): ?array {
        return $this -> routeEntity -> getDomains();
    }


    /**
     * Returns the route unique identifier
     * @return null|string
     */
    public function getIdentifier(): ?string {
        return $this -> routeEntity -> getIdentifier();
    }


    /**
     * Returns all the HTTP methods (GET, POST, DELETE, etc.) that the route is listening on
     * @return array
     */
    public function getMethod(): ?array {
        return $this -> routeEntity -> getMethod();
    }


    /**
     * Returns the route URL prefix
     * @return array
     */
    public function getPrefix(): ?array {
        return $this -> routeEntity -> getPrefix();
    }


    /**
     * Returns the locale
     * @return null|string
     */
    public function getLocale(): ?string {
        return $this -> routeEntity -> getLocale();
    }


    /**
     * Returns the route type (i.e. route or error)
     * @return null|string
     */
    public function getType(): ?string {
        return $this -> routeEntity -> getType();
    }


    /**
     * Returns the route URL
     * @return null|string
     */
    public function getUrl(): ?string {
        return $this -> routeEntity -> getUrl();
    }


    /**
     * Returns an array with regular expressions that matches the route parameters
     * @return null|array
     */
    public function getWhere(): ?array {
        return $this -> routeEntity -> getWhere();
    }


    /**
     * Returns all the parsed variables that matches the route variables with the url variables
     * @return array
     */
    public function getUrlVariables(): ?array {
        return $this -> routeEntity -> getUrlVariables();
    }


    /**
     * Returns a single URL variable that matches the route variables with the url variables
     * @param string $name The name of the variable
     * @return string
     */
    public function getUrlVariable(string $name): ?string {
        return $this -> routeEntity -> getUrlVariable($name);
    }


    /**
     * Returns a single variable that has been set with the assign method
     * @param string $name The name of the variable
     * @return mixed
     */
    public function getParam(string $name) {
        return $this -> routeEntity -> getParam($name);
    }


    /**
     * Returns all variables that has been set with the assign method
     * @return null|array
     */
    public function getParams(): ?array {
        return $this -> routeEntity -> getParams();
    }


    /**
     * Returns the route middleware
     * @return null|array
     */
    public function getMiddleware(): ?array {
        return $this -> routeEntity -> getMiddleware();
    }


    /**
     * Returns the route module
     * @return null|string
     */
    public function getModule(): ?string {
        return $this -> routeEntity -> getModule();
    }


    /**
     * Convert the current route to a URL
     * @param null|array $variables [optional] Variables to be inserted into the current route URL
     * @param null|string $domain [optional] A single domain/host that will be prepended to the current route URL
     * @return string
     */
    public function toUrl(?array $variables = [], string $domain = null): string {
        return $this -> routeEntity -> toUrl($variables, $domain);
    }


    /**
     * Set the locale
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void {
        $this -> routeEntity -> setLocale($locale);
    }


    /**
     * Assign new variable to the route
     * @param string|array $key The name of the variable
     * @param mixed $value The value of the variable
     * @param bool $merge True if the existing assigned variables needs to be merged into the new assigned variable, false if the new variable needs to clear every other previous assigned variable
     * @return void
     */
    public function assign($key, $value = null, bool $merge = true): void {
        $this -> routeEntity -> assign($key, $value, $merge);
    }


    /**
     * Forward browser/client to the current route
     * @return Forward
     */
    public function forward(): Forward {
        return $this -> routeEntity -> forward();
    }
}