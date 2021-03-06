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

use sFire\Db\QueryBuilder\Mysql\Types\Select;


/**
 * Class GatewaySelect
 * @package sFire\Bootstrap
 */
class GatewaySelect extends Select {


    use MysqliTrait;


    /**
     * @param null|string [optional] $type The type of union (all, distinct, distinctrow)
     * @return GatewayUnion
     */
    public function union(string $type = null): GatewayUnion {

        $union = new GatewayUnion($this -> build(), $type);
        $union -> setAdapter($this -> getAdapter());

        return $union;
    }
}