<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Mvc\Helpers;

use sFire\Http\Cookie;
use sFire\Routing\Forward;


/**
 * Class Response
 * @package sFire\Bootstrap
 */
class Response extends \sFire\Http\Response {


    /**
     * Contains instance of self
     * @var null|self
     */
    private static ?self $instance = null;


    /**
     * Returns instance of self
     * @return self
     */
    public static function getInstance(): self {

        if(null === static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }


    /**
     * Returns an instance of Cookie to be able to set and retrieve cookies
     * @return Cookie
     */
    public static function cookie(): Cookie {
        return Cookie :: getInstance();
    }


    /**
     * Redirect the client (usually a browser) to a route by giving a route identifier
     * @param string $identifier The route identifier
     * @return Forward
     */
    public static function forward(string $identifier) {
        return new Forward($identifier);
    }


    /**
     * Returns JSON encoded string by giving an array with data
     * @param array $data The data that needs to be encoded to JSON
     * @param bool $return When this parameter is set to true, it will return the information rather than print it
     * @return false|string|void
     */
    public static function json(array $data, bool $return = false) {

        if(true === $return) {
            return json_encode($data, JSON_INVALID_UTF8_IGNORE);
        }

        static::addHeader('Content-type', 'application/json');
        echo json_encode($data, JSON_INVALID_UTF8_IGNORE);
    }
}