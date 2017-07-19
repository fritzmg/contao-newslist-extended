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
							\StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->headline), true),
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
		// check if override is active and only override for default articles
		if (!$objModule->news_overrideRedirect && 'default' != $arrArticle['source'])
		{
			return;
		}

		// get current page object
		global $objPage;

		// check if a custom redirect page is set
		if ($arrArticle['jumpTo'] && null !== ($objTarget = \PageModel::findById($arrArticle['jumpTo'])))
		{
			// override the target page
			$objPage = $objTarget;
		}

		// build the href
		$strHref = \Controller::generateFrontendUrl($objPage->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/items/') . ((!\Config::get('disableAlias') && $arrArticle['alias'] != '') ? $arrArticle['alias'] : $arrArticle['id']), $objPage->rootLanguage, true);

		// encode href
		$strHref = ampersand($strHref);

		// update links
		$objTemplate->link = $strHref;
		$objTemplate->linkHeadline = $this->generateNewsLink( $strHref, $arrArticle['headline'], $arrArticle['headline']);
		$objTemplate->more = $this->generateNewsLink( $strHref, $arrArticle['headline'], $GLOBALS['TL_LANG']['MSC']['more'], true);
	}
}
