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

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'news_overrideRedirect';

if (stripos($GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'], ',news_readerModule,') === false) {
    $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(';{template_legend', ',news_readerModule;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);
}

$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(';{template_legend', ';{redirect_legend},news_overrideRedirect;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsarchive'] = str_replace(';{template_legend', ';{redirect_legend},news_overrideRedirect;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['newsarchive']);
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['news_overrideRedirect'] = 'jumpTo';

$GLOBALS['TL_DCA']['tl_module']['fields']['news_overrideRedirect'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_overrideRedirect'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['imgSize_featured'] = $GLOBALS['TL_DCA']['tl_module']['fields']['imgSize'];
$GLOBALS['TL_DCA']['tl_module']['fields']['imgSize_featured']['label'] = &$GLOBALS['TL_LANG']['tl_module']['imgSize_featured'];

if (class_exists(PaletteManipulator::class)) {
	PaletteManipulator::create()
		->addField('imgSize_featured', 'imgSize')
		->applyToPalette('newslist', 'tl_module')
		->applyToPalette('newsarchive', 'tl_module')
		->applyToPalette('newsreader', 'tl_module')
	;
} else {
	$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(',imgSize;', ',imgSize,imgSize_featured;', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);
	$GLOBALS['TL_DCA']['tl_module']['palettes']['newsarchive'] = str_replace(',imgSize;', ',imgSize,imgSize_featured;', $GLOBALS['TL_DCA']['tl_module']['palettes']['newsarchive']);
	$GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader'] = str_replace(',imgSize;', ',imgSize,imgSize_featured;', $GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader']);
}
