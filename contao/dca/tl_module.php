<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Newslist Extended extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'news_overrideRedirect';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['news_overrideRedirect'] = 'jumpTo';

PaletteManipulator::create()
    ->addLegend('redirect_legend', 'image_legend', PaletteManipulator::POSITION_BEFORE, true)
    ->addField('news_overrideRedirect', 'redirect_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('newslist', 'tl_module')
    ->applyToPalette('newsarchive', 'tl_module')
;

$GLOBALS['TL_DCA']['tl_module']['fields']['news_overrideRedirect'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['imgSize_featured'] = $GLOBALS['TL_DCA']['tl_module']['fields']['imgSize'];
$GLOBALS['TL_DCA']['tl_module']['fields']['imgSize_featured']['options_callback'] = ['contao.listener.image_size_options', '__invoke'];
$GLOBALS['TL_DCA']['tl_module']['fields']['imgSize_featured']['label'] = &$GLOBALS['TL_LANG']['tl_module']['imgSize_featured'];

PaletteManipulator::create()
    ->addField('imgSize_featured', 'imgSize')
    ->applyToPalette('newslist', 'tl_module')
    ->applyToPalette('newsarchive', 'tl_module')
    ->applyToPalette('newsreader', 'tl_module')
;

$GLOBALS['TL_DCA']['tl_module']['fields']['excludeCurrentNews'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 clr'],
    'sql' => ['type' => 'boolean', 'default' => false],
];

PaletteManipulator::create()
    ->addField('excludeCurrentNews', 'config_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('newslist', 'tl_module')
;
