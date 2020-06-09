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

use sFire\Http\Request as RequestObject;


/**
 * Class Request
 * @package sFire\Bootstrap
 */
class Request extends RequestObject {


    /**
     * Contains instance of Response
     * @var Request
     */
    private static ?self $instance = null;


    /**
     * Returns instance of the Request object
     * @return self
     */
    public static function getInstance(): self {

        if(null === static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }


    /**
     * Get variable from current route entity
     * @param mixed $key The name of the data
     * @param mixed $default Will return the given default value if data is not found
     * @return mixed
     */
    public static function fromRoute($key = null, $default = null) {
        return Router :: getInstance() -> getRoute() -> getUrlVariable($key) ?? $default;
    }
}