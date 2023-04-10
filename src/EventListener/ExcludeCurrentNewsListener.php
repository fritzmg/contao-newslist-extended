<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Newslist Extended extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoNewslistExtended\EventListener;

use Contao\Input;
use Contao\NewsModel;
use InspiredMinds\ContaoNewsFilterEvent\Event\NewsFilterEvent;

class ExcludeCurrentNewsListener
{
    public function __invoke(NewsFilterEvent $event): void
    {
        if (!$event->getModule()->excludeCurrentNews) {
            return;
        }

        $news = NewsModel::findPublishedByParentAndIdOrAlias(Input::get('auto_item', false, true), $event->getArchives());

        if (null === $news) {
            return;
        }

        $event->addColumn('tl_news.id != '.(int) $news->id);
    }
}
