<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Gateway\Mysqli;

use sFire\Db\QueryBuilder\Mysql\Build;
use sFire\Db\QueryBuilder\Mysql\Types\Union;


/**
 * Class GatewayUnion
 * @package sFire\Bootstrap
 */
class GatewayUnion extends Union {


    use MysqliTrait;


    /**
     * Constructor
     * @param Build $sqlBuild
     * @param null|string $unionType The type of union (all, distinct, distinctrow)
     */
    public function __construct(Build $sqlBuild, string $unionType = null) {
        parent :: __construct($sqlBuild, $unionType);
    }


    /**
     * Return a new instance of a GatewaySelect
     * @param mixed $parameters
     * @return GatewaySelect
     */
    protected function getSelectInstance($parameters): GatewaySelect {
        return new GatewaySelect($parameters);
    }
}