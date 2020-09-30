<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @package   newslist_extended
 * @author    Fritz Michael Gschwantner <https://github.com/fritzmg>
 * @license   LGPL-3.0+
 * @copyright Fritz Michael Gschwantner 2017
 */

use Contao\ClassLoader;

/**
 * Register the namespace
 */
ClassLoader::addNamespace('NewslistExtended');

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    // Classes
    'NewslistExtended\NewslistExtended' => 'system/modules/newslist_extended/classes/NewslistExtended.php',

    // Modules
    'NewslistExtended\ModuleNewsList'   => 'system/modules/newslist_extended/modules/ModuleNewsList.php'
));
