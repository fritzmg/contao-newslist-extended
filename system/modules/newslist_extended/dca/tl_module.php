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


$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'news_overrideRedirect';

if (stripos($GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'], ',news_readerModule,') === false) {
    $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(';{template_legend', ',news_readerModule;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);
}

$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(';{template_legend', ';{redirect_legend},news_overrideRedirect;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['news_overrideRedirect'] = 'jumpTo';

$GLOBALS['TL_DCA']['tl_module']['fields']['news_overrideRedirect'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_overrideRedirect'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);
