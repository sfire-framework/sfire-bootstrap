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

use ArrayIterator;
use sFire\Db\DbInterface;
use sFire\Db\Exception\BadMethodCallException;


/**
 * Class MysqliTrait
 * @package sFire\Db
 */
trait MysqliTrait {


    /**
     * Contains an adapter instance
     * @var null|DbInterface
     */
    private ?DbInterface $adapter = null;


    /**
     * Returns the adapter
     * @return DbInterface
     * @throws BadMethodCallException
     */
    public function getAdapter(): DbInterface {

        if(null === $this -> adapter) {
            throw new BadMethodCallException(sprintf('No adapter was set in "%s"', __METHOD__));
        }

        return $this -> adapter;
    }


    /**
     * Set the adapter
     * @param DbInterface $adapter
     * @return void
     */
    public function setAdapter(DbInterface $adapter): void {
        $this -> adapter = $adapter;
    }


    /**
     * Builds the query with bind parameters, executes it and returns the results as an array iterator
     * @return ArrayIterator
     */
    public function toArrayIterator(): ArrayIterator {

        $sql = $this -> build();
        return $this -> getAdapter() -> query($sql -> getQuery(), $sql -> getParameters()) -> toArrayIterator();
    }


    /**
     * Builds the query with bind parameters and executes the query
     * @return bool
     */
    public function exec(): bool {

        $sql = $this -> build();
        return $this -> getAdapter() -> query($sql -> getQuery(), $sql -> getParameters()) -> execute();
    }
}