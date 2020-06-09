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

use sFire\Config\Path as ConfigPath;
use sFire\Config\App;


/**
 * Class Path
 * @package sFire\Bootstrap
 */
class Path {


    /**
     * Contains instance of Request
     * @var null|Path
     */
    private static ?self $instance = null;


    /**
     * Returns instance of Path
     * @return self
     */
    public static function getInstance(): self {

        if(null === static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }


    /**
     * Returns the configuration directory for a optional given module
     * @return string
     */
    public function getConfig(): string {
        return static :: getPath() . App :: getInstance() -> get('directory.config');
    }


    /**
     * Returns the configuration directory for a optional given module
     * @return string
     */
    public function getControllers(): string {
        return static :: getPath() . App :: getInstance() -> get('directory.controller');
    }


    /**
     * Returns the helpers directory for a optional given module
     * @return string
     */
    public function getHelpers(): string {
        return static :: getPath() . App :: getInstance() -> get('directory.helper');
    }


    /**
     * Returns the middleware directory for a optional given module
     * @return string
     */
    public function getMiddleware(): string {
        return static :: getPath() . App :: getInstance() -> get('directory.middleware');
    }


    /**
     * Returns the models directory for a optional given module
     * @return string
     */
    public function getModels(): string {
        return static :: getPath() . App :: getInstance() -> get('directory.model');
    }


    /**
     * Returns the translations directory for a optional given module
     * @return string
     */
    public function getTranslations(): string {
        return static :: getPath() . App :: getInstance() -> get('directory.translation');
    }


    /**
     * Returns the validators directory for a optional given module
     * @return string
     */
    public function getValidators(): string {
        return static :: getPath() . App :: getInstance() -> get('directory.validator');
    }


    /**
     * Returns the views directory for a optional given module
     * @return string
     */
    public function getViews(): string {
        return static :: getPath() . App :: getInstance() -> get('directory.view');
    }


    /**
     * Returns the root path for the current module
     * @return string
     */
    private function getPath(): string {

        $module = Module::getModuleName() ?? Module :: getRoute() -> getModule();
        return ConfigPath :: getInstance() -> get('modules') . $module . DIRECTORY_SEPARATOR;
    }
}