<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\Controller;
use Contao\System;

// Apply after all DCA files have been loaded, regardless of bundle order.
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = static function (): void {
    if (!isset($GLOBALS['TL_DCA']['tl_content']['palettes']['newsfilter'])) {
        return;
    }

    $GLOBALS['TL_LANG']['tl_content']['redirect_legend'] ??= $GLOBALS['TL_LANG']['tl_module']['redirect_legend'] ?? 'Redirect';

    PaletteManipulator::create()
        ->addLegend('redirect_legend', 'link_legend', PaletteManipulator::POSITION_AFTER)
        ->addField('news_overrideRedirect', 'redirect_legend', PaletteManipulator::POSITION_APPEND)
        ->applyToPalette('newsfilter', 'tl_content');
};

// Define fields at DCA load time so database schema discovery sees them.
if (class_exists(\Codefog\NewsCategoriesBundle\ContentElement\NewsFilterElement::class)) {
    Controller::loadDataContainer('tl_module');
    System::loadLanguageFile('tl_module');

    $GLOBALS['TL_DCA']['tl_content']['fields']['news_overrideRedirect'] = $GLOBALS['TL_DCA']['tl_module']['fields']['news_overrideRedirect'];
    $GLOBALS['TL_DCA']['tl_content']['fields']['news_overrideRedirect']['label'] = &$GLOBALS['TL_LANG']['tl_module']['news_overrideRedirect'];
    $GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo'] ??= $GLOBALS['TL_DCA']['tl_module']['fields']['jumpTo'];
    if (!isset($GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo']['label'])) {
        $GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo']['label'] = &$GLOBALS['TL_LANG']['tl_module']['jumpTo'];
    }
    $GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'news_overrideRedirect';
    $GLOBALS['TL_DCA']['tl_content']['subpalettes']['news_overrideRedirect'] = 'jumpTo';
    $GLOBALS['TL_DCA']['tl_content']['fields']['news_overrideRedirect']['eval']['submitOnChange'] = true;
}
