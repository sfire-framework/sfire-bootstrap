<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Mapper\Result;

use ArrayIterator;


/**
 * Class ResultIterator
 * @package sFire\Bootstrap
 */
class ResultIterator extends ArrayIterator {


    /**
     * Contains if the results should be converted to an array
     */
    public const TYPE_ARRAY = 'array';


    /**
     * Contains if the results should be converted to a JSON string
     */
    public const TYPE_JSON = 'json';


    /**
     * Contains if the results should be converted to a STDClass
     */
    public const TYPE_OBJECT = 'object';


    /**
     * Contains the result type (array, json or object)
     * @var string
     */
    private string $resultType = self::TYPE_ARRAY;


    /**
     * Constructor
     * @param array $data
     * @param string $resultType
     */
    public function __construct(array $data, $resultType = self::TYPE_ARRAY) {

        parent::__construct($data);
        $this -> resultType = $resultType;
    }


    /**
     * Return current array entry as an STD class
     * @return mixed The current array entry as an STD class
     */
    public function current() {

        $current = parent::current();

        switch($this -> resultType) {

            case self::TYPE_OBJECT : return (object) $current; break;
            case self::TYPE_JSON   : return json_encode($current, JSON_INVALID_UTF8_IGNORE); break;
            default                : return (array) $current; break;
        }
    }
}