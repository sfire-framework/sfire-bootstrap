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

use sFire\Db\QueryBuilder\Mysql\Types\Call;


/**
 * Class GatewaySelect
 * @package sFire\Bootstrap
 */
class GatewayCall extends Call {
    use MysqliTrait;
}