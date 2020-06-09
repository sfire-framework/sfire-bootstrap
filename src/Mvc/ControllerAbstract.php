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

use sFire\Bootstrap\Mvc\Helpers\MvcHelperContainer;
use sFire\Bootstrap\Mvc\Helpers\MvcHelperTrait;
use sFire\Bootstrap\Mapper\MapperAbstract;


/**
 * Class ControllerAbstract
 * @package sFire\Mvc
 */
abstract class ControllerAbstract {


    use MvcHelperTrait;


    /**
     *
     * @param string $mapper
     * @param bool $enableCache
     * @return MapperAbstract
     */
    public function mapper(string $mapper, bool $enableCache = true): MapperAbstract {

        $container = new MvcHelperContainer();

        if(false === $container -> has('mapper')) {
            $container -> set('mapper', []);
        }

        $namespace = Mvc :: getMvcClass('mapper', $this -> router() -> getRoute() -> getModule(), $mapper);
        $mapper    = $container -> get('mapper.' . $namespace);

        if(null === $mapper || false === $enableCache) {

            $mapper = new $namespace();
            $container -> add('mapper.' . $namespace, $mapper);
        }

        return $mapper;
    }
}