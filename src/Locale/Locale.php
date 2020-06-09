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
use sFire\Localization\Translation;


/**
 * Class Locale
 * @package sFire\Bootstrap
 */
class Locale {


    /**
     * Returns instance of Translation for translating tet
     * @return Translation
     */
    public function translation(): Translation {

        $translation =  Translation :: getInstance();
        $translation -> setLanguage(App::getInstance()->get('locale'));

        return $translation;
    }


    /**
     * Returns instance of Language for setting and retrieving current language
     * @return Language
     */
    public function language(): Language {
        return Language :: getInstance();
    }
}