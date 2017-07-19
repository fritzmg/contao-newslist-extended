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

if (class_exists('\NewsCategories\ModuleNewsList')) {
    class ParentModuleNewsList extends \NewsCategories\ModuleNewsList {}
} else {
    class ParentModuleNewsList extends \Contao\ModuleNewsList {}
}

class ModuleNewsList extends ParentModuleNewsList
{
	/**
	 * Show news reader if applicable.
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			return parent::generate();
		}

		// Show the news reader if an item has been selected
		if ($this->news_readerModule > 0 && (isset($_GET['items']) || (\Config::get('useAutoItem') && isset($_GET['auto_item']))))
		{
			return \Controller::getFrontendModule($this->news_readerModule, $this->strColumn);
		}

		return parent::generate();
	}
}
