<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Mvc;

use sFire\Config\App;
use sFire\DataControl\TypeString;
use sFire\Routing\Exception\LogicException;
use sFire\Routing\RouteEntity;
use sFire\Routing\Router;
use sFire\Bootstrap\Exception\BadMethodCallException;
use sFire\Bootstrap\Exception\UnexpectedValueException;
use sFire\Bootstrap\Middleware\MiddlewareContainer;


/**
 * Class Mvc
 * @package sFire\Bootstrap
 */
class Mvc {


    /**
     * Holds an instance of a Route
     * @var RouteEntity
     */
	private ?RouteEntity $route = null;


    /**
     * Holds an instance of a MiddlewareContainer
     * @var MiddlewareContainer
     */
    private MiddlewareContainer $middlewareContainer;


    /**
     * Constructor
     */
	public function __construct() {
	    $this -> middlewareContainer = new MiddlewareContainer();
    }


    /**
     * Set a new Route instance and validates it
     * @param RouteEntity $route
     */
    public function useRoute(RouteEntity $route): void {

	    //Todo: $route -> validate();

        //Set the local
        if($local = $route -> getLocale()) {
            App :: getInstance() -> set('locale', $local);
        }

	    Router :: setRoute($route);
	    $this -> route = $route;
    }


    /**
     * Execute all the before and after middleware en execute the controller with corresponding action
     * @return void
     * @throws UnexpectedValueException
     */
    public function navigate(): void {

        //Check if a route has been set
        if(null === $this -> route) {
            throw new UnexpectedValueException('Route is not set. Set a new route with the setRoute() method first.');
        }

        //Reset the middleware counters
        $this -> middlewareContainer -> reset();

        //Preload middleware
        $this -> preloadMiddleware($this -> route);

        //Execute the before middleware
	    $this -> executeBeforeMiddleware();

        //Check if all the before middleware has been executed and that the last middleware want's to go to the controller by calling the next method
        if(false === $this -> middlewareContainer -> isEmpty()) {
            return;
        }

        //Execute the controller when all before middleware allows it
        $this -> executeController();

        //Execute the after middleware
        $this -> executeAfterMiddleware();
    }


    /**
     * Execute all before methods from all middleware
     * @return void
     */
    public function executeBeforeMiddleware(): void {

        $this -> middlewareContainer -> mode('before');
        $this -> middlewareContainer -> next();
    }


    /**
     * Execute all after methods from all middleware
     * @return void
     */
    public function executeAfterMiddleware(): void {

        $this -> middlewareContainer -> mode('after');
        $this -> middlewareContainer -> next();
    }


    /**
     * Execute route controller and action method
     * @return void
     * @throws BadMethodCallException
     */
    public function executeController(): void {

        if(null === $this -> route -> getController() || null === $this -> route -> getAction()) {
            return;
        }

	    $class      = static :: getMvcClass('controller', $this -> route -> getModule(), $this -> route -> getController());
        $controller = new $class;

        //Execute start method if controller supports it
        if(true === is_callable([$controller, '__start'])) {
            call_user_func_array([$controller, '__start'], []);
        }

        //Execute action
        $action = App :: getInstance() -> get('prefix.action') . $this -> route -> getAction();

        //Trigger error if main function does not exists
        if(false === is_callable([$controller, $action])) {
            throw new BadMethodCallException(sprintf('Method "%s" does not exists in "%s" controller', App :: getInstance() -> get('prefix.action') . ucfirst((string) $this -> route -> getAction()), $this -> route -> getController()));
        }

        //Execute main controller action
        call_user_func_array([$controller, $action], []);

        //Execute end method if controller supports it
        if(true === is_callable([$controller, '__end'])) {
            call_user_func_array([$controller, '__end'], []);
        }
    }


    /**
     * Converts and concat parameters to a namespace and classname and returns it
     * @param string $module The name of the module
     * @param string $filename The name of the file
     * @param string $prefix The prefix for the filename
     * @param string $postfix The postfix for the filename
     * @param string $directory The directory path to the files
     * @return string The path to the module namespace/class
     */
    public static function getModuleNameSpace(string $module, string $filename, string $prefix, string $postfix, string $directory): string {

        $directory = implode('\\', array_map('ucfirst', explode(DIRECTORY_SEPARATOR, $directory)));
        return sprintf('%s\\%s%s%s%s', $module, $directory, $prefix, $filename, $postfix);
    }


    /**
     * Returns the namespace and class for a given MVC file
     * @param string $type
     * @param string $module
     * @param string $path
     * @return string
     * @throws LogicException
     */
    public static function getMvcClass(string $type, string $module, string $path): string {

        $config = App :: getInstance();
        $class  = static :: getModuleNameSpace($module, TypeString :: toPascalCase($path), $config -> get('prefix.' . $type), $config -> get('postfix.' . $type), $config -> get('directory.' . $type));

        if(false === class_exists($class)) {
            throw new LogicException(sprintf('Class "%s" with module "%s" does not exists', $class, $module));
        }

        return $class;
    }


    /**
     * Loads all the middleware classes and saves them for later use for a single route
     * @param RouteEntity $route
     * @return void
     */
	private function preloadMiddleware(RouteEntity $route): void {

	    $middleware = $route -> getMiddleware();

        if(null === $middleware) {
            return;
        }

        foreach($middleware as $class) {

			$this -> middlewareContainer -> add($class, 'before');
			$this -> middlewareContainer -> add($class, 'after');
		}
	}
}