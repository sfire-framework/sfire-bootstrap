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

use sFire\Config\Path;
use sFire\Config\Provider;
use sFire\Http\Cookie;
use sFire\Localization\Translation;
use sFire\Bootstrap\Exception\BadMethodCallException;
use sFire\Bootstrap\Locale\Language;
use sFire\Bootstrap\Locale\Locale;
use sFire\Bootstrap\Module\Module;


/**
 * Trait MvcHelperTrait
 * @package sFire\Bootstrap
 */
trait MvcHelperTrait {


    /**
     * Contains all the cache for the providers
     * @var array
     */
    private array $providerCache = [];


    /**
     * Provider helper method to retrieve configuration settings
     * @param string $name The name of the provider that should be returned
     * @param array $arguments Arguments that will be injected into the provider
     * @return mixed
     * @throws BadMethodCallException
     */
	public function provider(string $name, ...$arguments) {

        $provider = Provider :: getInstance() -> get($name);

        if(false === is_callable($provider)) {
            throw new BadMethodCallException(sprintf('"%s" is not a known or valid provider', $name));
        }

        if(count($arguments) > 0) {
            return call_user_func_array($provider, $arguments);
        }

        if(false === isset($this -> providerCache[$name])) {
            $this -> providerCache[$name] = $provider();
        }

        return $this -> providerCache[$name];
	}


    /**
     * Config helper method to retrieve configuration settings
     * @param null|string $property
     * @param null $default
     * @param null|string $module
     * @return mixed
     */
    public function config(string $property = null, $default = null, string $module = null) {

        Module::setModuleName($module);
        return Module :: getConfig($property, $default);
    }


    /**
     * Returns an instance of Path
     * @return Path
     */
    public function path(): Path {
        return Path :: getInstance();
    }


    /**
     * Request helper method to retrieve a request object
     * @return Request
     */
    public function request(): Request {
        return Request :: getInstance();
    }


    /**
     * Response helper method to retrieve a request object
     * @return Response
     */
    public function response(): Response {
        return Response :: getInstance();
    }


    /**
     * Returns a route based on a route identifier. If no identifier is provider, the current route will be returned
     * @return Router
     */
    public function router() {
        return Router :: getInstance();
    }


    /**
     * Returns an instance of Cookie to be able to set and retrieve cookies
     * @return Cookie
     */
    public function cookie() {
        return Cookie :: getInstance();
    }


    /**
     * Returns an instance of Translation
     * @return Translation
     */
    public function translation(): Translation {
        return (new Locale()) -> translation();
    }


    /**
     * Returns an instance of Language
     * @return Language
     */
    public function language(): Language {
        return (new Locale()) -> language();
    }


    /**
     * Returns an instance of Module to able to retrieve module config and paths
     * @param null|string $moduleName
     * @return Module
     */
    public function module(string $moduleName = null): Module {

        $instance = Module :: getInstance();
        $instance -> setModuleName($moduleName);

        return $instance;
    }
}