<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Entity\Mysqli;

use sFire\Bootstrap\Entity\EntityAbstract;
use sFire\Bootstrap\Exception\RuntimeException;
use stdClass;


/**
 * Class MysqliEntityAbstract
 * @package sFire\Bootstrap
 */
abstract class MysqliEntityAbstract extends EntityAbstract {


    /**
     * Creates new entity. If entity already exists, if will be updated.
     * @return bool
     */
    public function save(): bool {

        //Execute user defined before save function
        $this -> beforeSave();

        $identifiers = [];
        $values      = [];

        foreach($this -> _properties as $property) {

            if(true === $property -> isPrimary && true === in_array($property -> type, ['int', null])) {
                $identifiers[] = $property;
            }

            //Skip generated columns (but don't skip generated autoincrement primary key)
            if(true === $property -> isGenerated && false === $property -> isPrimary) {
                continue;
            }

            $column = $property -> name;
            $value  = $this -> getValueFromProperty($property);
            
            if(true === is_array($value) && 'json' === $property -> type) {
                $value = json_encode($value, JSON_INVALID_UTF8_IGNORE);
            }
            
            $values[$column] = $value;
        }

        //Save the entity
        $success = $this -> getGateway() -> saveEntity($values);

        //Set new id if last id is not null
        $id  	  = $this -> getGateway() -> getLastInsertedId();
        $affected = $this -> getGateway() -> getAffectedRows();

        if($affected > 0 && $id > 0) {

            foreach($identifiers as $property) {
                $this -> {$property -> setter}($id);
            }
        }

        //Execute user defined after save function
        $this -> afterSave();

        return $success;
    }


    /**
     * Refreshes the current entity based on identifier(s) and value(s)
     * @return bool
     */
    public function reload(): bool {

        $success = false;

        //Execute user defined before reload function
        $this -> beforeReload();
        $conditions = $this -> generateWhereCondition();

        //Fetch the new data and fill the current entity with the new data
        $data = $this -> getGateway() -> reloadEntity($conditions -> sql, $conditions -> parameters);

        if(null !== $data) {

            $this -> fromArray($data);
            $success = true;
        }

        //Execute user defined after reload function
        $this -> beforeReload();

        return $success;
    }


    /**
     * Deletes the current entity based on identifier(s)
     * @return bool
     */
    public function delete(): bool {

        //Execute user defined before deleting function
        $this -> beforeDelete();
        $conditions = $this -> generateWhereCondition();

        //Delete the entity from the database and clear the data from the entity
        $success = $this -> getGateway() -> deleteEntity($conditions -> sql, $conditions -> parameters);
        $this -> clear();

        //Execute user defined after deleting function
        $this -> afterDelete();

        return $success;
    }


    /**
     * Generates the where condition for selecting the current entity from a database
     * @return stdClass
     * @throws RuntimeException
     */
    private function generateWhereCondition(): stdClass {

        $where      = [];
        $parameters = [];
        $unique     = [];

        foreach($this -> _properties as $property) {

            if(true === $property -> isPrimary) {

                $where[] = sprintf('`%s` = ?', $property -> name);
                $parameters[] = $this -> getValueFromProperty($property);
            }

            if(true === $property -> unique) {
                $unique[] = $property;
            }
        }

        if(0 === count($where)) {

            if(0 === count($unique)) {
                throw new RuntimeException('Could not reload or delete current entity. Failed to determine a primary or unique column for selecting the entity from database.');
            }

            foreach($unique as $property) {

                $where[] = sprintf('`%s` = ?', $property -> name);
                $value = $this -> getValueFromProperty($property);

                if(null === $value) {
                    throw new RuntimeException(sprintf('Could not reload or delete current entity. Multiple entries plausible on executing select statement for retrieving entity when unique column "%s" may contain null values.', $property -> name));
                }

                $parameters[] = $value;
            }
        }

        return (object) ['sql' => $where, 'parameters' => $parameters];
    }
}