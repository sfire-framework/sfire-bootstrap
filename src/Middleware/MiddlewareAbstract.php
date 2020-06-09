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

use sFire\Bootstrap\Mvc\Helpers\MvcHelperTrait;


/**
 * Class AbstractMiddleware
 * @package sFire\Bootstrap
 */
class MiddlewareAbstract {


    use MvcHelperTrait;


    /**
     * When called, the next middleware will be called.
     * If there is none left, it will continue to load the controller of the route or stop executing if the controller already been executed
     * @return void
     */
	public function next(): void {
		MiddlewareContainer :: next();
	}
}