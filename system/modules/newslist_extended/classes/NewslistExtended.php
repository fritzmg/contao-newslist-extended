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


namespace NewslistExtended;

use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\Environment;
use Contao\PageModel;
use Jean85\PrettyVersions;

class NewslistExtended
{
	/**
	 * Generates a HTML link for a news entry.
	 *
	 * @param string $strHref
	 * @param string $strTitle
	 * @param string $strText
	 * @param boolean $blnIsReadmore
	 *
	 * @return string
	 */
	private function generateNewsLink($strHref, $strTitle, $strText, $blnIsReadMore = false)
	{
		return sprintf('<a href="%s" title="%s" itemprop="url">%s%s</a>',
							$strHref,
							specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $strTitle), true),
							$strText,
							($blnIsReadMore ? ' <span class="invisible">'.$strTitle.'</span>' : ''));
	}


	/**
	 * Overrides the news link if applicable
	 *
	 * @param \FrontendTemplate $objTemplate
	 * @param array $arrArticle
	 * @param \Module $objModule
	 *
	 * @return void
	 */
	public function parseArticles($objTemplate, $arrArticle, $objModule)
	{
		// check if the source is "default"
		if ('default' != $arrArticle['source'])
		{
			return;
		}

		// check if this is the newsreader
		if ($objModule->type == 'newsreader')
		{
			// get the current uri
			$strCurrentUri = Environment::get('uri');

			// get the canonical uri
			$strCanonicalUri = (strpos($objTemplate->link, 'http') !== 0 ? Environment::get('base') : '') . $objTemplate->link;

			// check if Uris are the same
			if ($strCurrentUri != $strCanonicalUri)
			{
				// insert canonical tag
				$GLOBALS['TL_HEAD'][] = '<link rel="canonical" href="'.$strCanonicalUri.'">';
			}

			// don't do anything else
			return;
		}

		// check if a custom redirect page is set and the article has no redirects on its own
		if ('default' === $arrArticle['source']
		 && $objModule->news_overrideRedirect 
		 && $objModule->jumpTo 
		 && null !== ($objTarget = PageModel::findById($objModule->jumpTo)))
		{
			// build the href
			$strHref = Controller::generateFrontendUrl($objTarget->row(), ((Config::get('useAutoItem') && !Config::get('disableAlias')) ?  '/' : '/items/') . ((!Config::get('disableAlias') && $arrArticle['alias'] != '') ? $arrArticle['alias'] : $arrArticle['id']), $objTarget->rootLanguage, true);

			// encode href
			$strHref = ampersand($strHref);

			// update links
			if (!$arrArticle['fullsize']) {
				$objTemplate->href = $strHref;
				$objTemplate->imageHref = $strHref;
			}
			
			$objTemplate->link = $strHref;
			$objTemplate->linkHeadline = $this->generateNewsLink( $strHref, $arrArticle['headline'], $arrArticle['headline']);
			$objTemplate->more = $this->generateNewsLink( $strHref, $arrArticle['headline'], $GLOBALS['TL_LANG']['MSC']['more'], true);
		}
	}

	/**
	 * Checks whether to add the newsreader feature.
	 */
	public static function addNewsReader(): bool
	{
		if (!class_exists(ContaoCoreBundle::class)) {
			return true;
		}

		try {
			$contaoVersion = PrettyVersions::getVersion('contao/core-bundle');
		} catch (\OutOfBoundsException $e) {
			$contaoVersion = PrettyVersions::getVersion('contao/contao');
		}
 
		return version_compare($contaoVersion->getShortVersion(), '4.7.0', '<');
	}
}
