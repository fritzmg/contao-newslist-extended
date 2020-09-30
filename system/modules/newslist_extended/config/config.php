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

use NewslistExtended\ModuleNewsList;
use NewslistExtended\NewslistExtended;

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['parseArticles'][] = [NewslistExtended::class, 'parseArticles'];

/**
 * Frontend modules
 */
if (NewslistExtended::addNewsReader()) {
    $GLOBALS['FE_MOD']['news']['newslist'] = ModuleNewsList::class;
}
