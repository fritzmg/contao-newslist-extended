<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoNewslistExtended\EventListener;

use Contao\Input;
use Contao\NewsModel;
use InspiredMinds\ContaoNewsFilterEvent\Event\NewsFilterEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
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
