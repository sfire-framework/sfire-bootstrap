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

use sFire\Db\DbInterface;
use sFire\Db\QueryBuilder\Mysql\QueryBuilder;
use sFire\Bootstrap\Gateway\GatewayAbstract;


/**
 * Class MysqliGatewayAbstract
 * @package sFire\Bootstrap
 */
abstract class MysqliGatewayAbstract extends GatewayAbstract {


    use MysqliTrait;


    /**
     * Contains the table name
     * @var null|string
     */
    private ?string $tableName = null;


    /**
     * Sets the table name for the current gateway
     * @param string $tableName The table name
     * @return void
     */
    public function setTable(string $tableName): void {
        $this -> tableName = $tableName;
    }


    /**
     * Returns the current gateway table name
     * @return null|string
     */
    public function getTable(): ?string {
        return $this -> tableName;
    }


    /**
     * Returns a new instance of the Mysqli query builder
     * @return QueryBuilder
     */
    public function build(): QueryBuilder {
        return new QueryBuilder();
    }


    /**
     * Add a select clause
     * @param mixed ...$columns
     * @return GatewaySelect
     */
    public function select(array $columns = ['*']): GatewaySelect {

        $select = new GatewaySelect($columns);
        $select -> setAdapter($this -> getAdapter());
        $select -> table($this -> getTable());

        return $select;
    }


    /**
     * Insert a record to the database
     * @param mixed $columns
     * @param bool $ignoreErrors
     * @return GatewayInsert
     */
    public function insert($columns, bool $ignoreErrors = false): GatewayInsert {

        $insert = new GatewayInsert($columns);
        $insert -> setAdapter($this -> getAdapter());
        $insert -> table($this -> getTable()) -> ignore($ignoreErrors);

        return $insert;
    }


    /**
     * Delete a record(s) from the database
     * @return GatewayDelete
     */
    public function delete(): GatewayDelete {

        $delete = new GatewayDelete();
        $delete -> table($this -> getTable());
        $delete -> setAdapter($this -> getAdapter());

        return $delete;
    }


    /**
     * Updates record(s) from the database
     * @param array $columns
     * @param bool $ignoreErrors
     * @return GatewayUpdate
     */
    public function update(array $columns, bool $ignoreErrors = false): GatewayUpdate {

        $update = new GatewayUpdate($columns);
        $update -> setAdapter($this -> getAdapter());
        $update -> table($this -> getTable()) -> ignore($ignoreErrors);

        return $update;
    }


    /**
     * Call a stored procedure
     * @param string $functionName The name of the stored procedure
     * @param array $parameters An array with parameters
     * @return GatewayCall
     */
    public function call(string $functionName, array $parameters = []): GatewayCall {

        $call = new GatewayCall($functionName);
        $call -> setAdapter($this -> getAdapter());
        $call -> parameters($parameters);

        return $call;
    }


    /**
     * Replaces a record from the database
     * @param array $columns
     * @param bool $ignoreErrors
     * @return GatewayReplace
     */
    public function replace($columns, bool $ignoreErrors = false): GatewayReplace {

        $insert = new GatewayReplace($columns);
        $insert -> setAdapter($this -> getAdapter());
        $insert -> table($this -> getTable()) -> ignore($ignoreErrors);

        return $insert;
    }


    /**
     * Execute a raw query
     * @param string $query
     * @param array $params
     * @return DbInterface
     */
    public function query(string $query, array $params = []): DbInterface {
        return $this -> getAdapter() -> query($query, $params);
    }


    /**
     * Returns the last inserted id
     * @return null|int
     */
    final public function getLastInsertedId(): ?int {
        return $this -> getAdapter() -> getLastInsertedId();
    }

    /**
     * Returns the affected rows from last statement
     * @return int
     */
    final public function getAffectedRows(): int {
        return $this -> getAdapter() -> getAffectedRows();
    }


    /**
     * Insert the current entity. Entity will be updated if already exists.
     * @param array $values
     * @return bool
     */
    final public function saveEntity(array $values): bool {

        $sql = $this -> build() -> insert($values) -> table($this -> getTable()) -> duplicate() -> build();
        return $this -> getAdapter() -> query($sql -> getQuery(), $sql -> getParameters()) -> execute();
    }


    /**
     * Refreshes the current entity based on identifier(s) and value(s)
     * @param array $sql
     * @param array $conditions
     * @return null|array
     */
    final public function reloadEntity(array $sql, array $conditions = []): ?array {

        $sql = $this -> build() -> select() -> table($this -> getTable()) -> where(...$sql) -> limit(1) -> bind($conditions) -> build();

        //Fetch the new data and fill the current entity with the new data
        return $this -> getAdapter() -> query($sql -> getQuery(), $sql -> getParameters()) -> toArrayIterator() -> current();
    }


    /**
     * Delete the current entity based on identifier(s)
     * @param array $sql
     * @param array $conditions
     * @return bool
     */
    final public function deleteEntity(array $sql, array $conditions = []): bool {

        //Build sql
        $sql = $this -> build() -> delete() -> table($this -> getTable()) -> where(...$sql) -> bind($conditions) -> build();

        //Delete the entity from the database and clear the data from the entity
        return $this -> getAdapter() -> query($sql -> getQuery(), $sql -> getParameters()) -> execute();
    }

}