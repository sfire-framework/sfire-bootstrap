<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap;

use Composer\Autoload\ClassLoader;
use sFire\Bootstrap\Mvc\Mvc;
use sFire\Debugging\ErrorHandler;
use sFire\Http\Response;
use sFire\Routing\Router;
use sFire\Routing\RouteEntity;
use sFire\Config\App;
use sFire\Config\Path;
use sFire\Config\Provider;
use sFire\Config\Routes;


/**
 * Class Boot
 * @package sFire\Mvc
 */
class Boot {


    /**
     * Contains if routing should be executed or not
     * @var bool
     */
    private bool $routing = true;


    /**
     * Contains the composer autoloader instance
     * @var ClassLoader
     */
    private ?ClassLoader $autoloader = null;


    /**
     * Constructor
     * @param ClassLoader $autoloader
     */
    public function __construct(ClassLoader $autoloader) {
        $this -> setAutoloader($autoloader);
    }


    /**
     * Sets if routing should be enabled or not
     * @param bool $routing
     * @return void
     */
    public function setRouting(bool $routing): void {
        $this -> routing = $routing;
    }


    /**
     * Sets the autoloader
     * @param ClassLoader $autoloader
     * @return void
     */
    public function setAutoloader(ClassLoader $autoloader): void {
        $this -> autoloader = $autoloader;
    }


    /**
     * Executes MVC initializing
     * @return void
     */
    public function exec(): void {

        $this -> loadConfigFiles();
        $this -> loadDebugging();
        $this -> AddModulesToAutoloader();
        $this -> loadRoutes();

        //Check if the router is enabled
        if(false === $this -> routing) {
            return;
        }

        $route = $this -> getMatchedRoute();

        if(null === $route) {

            Response :: setStatus(404);
            return;
        }

        $this -> executeMvc($route);
    }


    /**
     * Loads the config files
     * @return void
     */
    private function loadConfigFiles(): void {

        //Load the path config
        new Path();

        //Load the app config
        new App();

        //Load the provider config
        new Provider();
    }


    /**
     * Load error and exception handler
     * @return void
     */
    private function loadDebugging(): void {

        $errorHandler = new ErrorHandler();
        $errorHandler -> setLogDirectory(Path :: getInstance() -> get('log-error'));
        $errorHandler -> setOptions([

            'write'     => App :: getInstance() -> get('debug.write.enabled', true),
            'display'   => App :: getInstance() -> get('debug.display.enabled', true),
            'ip'        => App :: getInstance() -> get('debug.display.ip.*', [])
        ]);
    }


    /**
     * Add all enabled modules found in app.php to the autoloader
     * @return void
     */
    private function AddModulesToAutoloader(): void {

        $modules = App :: getInstance() -> get('modules.*');

        if(true === is_array($modules)) {

            foreach($modules as $module) {
                $this -> autoloader -> addPsr4(sprintf('%s\\', $module), sprintf('%s%s%s', Path :: getInstance() -> get('modules'), $module, DIRECTORY_SEPARATOR));
            }

            $this -> autoloader -> register();
        }
    }


    /**
     * Load routes for url matching
     * @return void
     */
    private function loadRoutes(): void {

        new Routes();
        Router :: formatRoutes();
    }


    /**
     * Returns the matched route based on the current URL
     * @return RouteEntity
     */
    private function getMatchedRoute(): ?RouteEntity {

        $route = Router :: getMatchedRoute();

        if(null === $route) {
            $route = Router :: getErrorRoute('404');
        }

        return $route;
    }


    /**
     * Execute MVC based on given route
     * @param RouteEntity $route
     * @return void
     */
    private function executeMvc(RouteEntity $route): void {

        $mvc = new Mvc();
        $mvc -> useRoute($route);
        $mvc -> navigate();
    }
}