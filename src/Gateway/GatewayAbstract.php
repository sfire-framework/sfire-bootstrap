<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Gateway;

use sFire\Bootstrap\Mvc\Helpers\MvcHelperTrait;
use sFire\DataControl\Translators\StringTranslator;


/**
 * Class GatewayAbstract
 * @package sFire\Bootstrap
 */
abstract class GatewayAbstract {


    use MvcHelperTrait;


    /**
     * Filters data from a given array based on an array of paths with key value to return a new array with the extracted data
     * @param array $response
     * @param array $paths
     * @return array
     */
    public function filter(array &$response, array $paths) {

        $output     = [];
        $extractTranslator = new StringTranslator($response);

        foreach($paths as $key => $path) {

            $extracted = $extractTranslator -> get($path);

            if(null !== $extracted) {

                $insertTranslator = new StringTranslator($output);
                $insertTranslator -> add($key, $extracted);
            }
        }

        return $output;
    }


    /**
     * Insert the current entity. Entity will be updated if already exists.
     * @param array $values
     * @return bool
     */
    abstract public function saveEntity(array $values): bool;


    /**
     * Refreshes the current entity based on identifier(s) and value(s)
     * @param array $sql
     * @param array $conditions
     * @return null|array
     */
    abstract public function reloadEntity(array $sql, array $conditions = []): ?array;


    /**
     * Delete the current entity based on identifier(s)
     * @param array $sql
     * @param array $conditions
     * @return bool
     */
    abstract public function deleteEntity(array $sql, array $conditions = []): bool;
}