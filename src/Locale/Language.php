<?php
/**
 * sFire Framework (https://sfire.io)
 *
 * @link      https://github.com/sfire-framework/ for the canonical source repository
 * @copyright Copyright (c) 2014-2020 sFire Framework.
 * @license   http://sfire.io/license BSD 3-CLAUSE LICENSE
 */

declare(strict_types=1);

namespace sFire\Bootstrap\Locale;

use sFire\Config\App;


/**
 * Class Language
 * @package sFire\Bootstrap
 */
class Language {


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
     * Sets the locale language
     * @param string $locale
     * @return void
     */
    public function set(string $locale): void {
        App :: getInstance() -> set('locale', $locale);
    }


    /**
     * Returns the locale language
     * @return mixed
     */
    public function get() {
        return App :: getInstance() -> get('locale');
    }
}