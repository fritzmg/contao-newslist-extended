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

use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\FrontendTemplate;
use Contao\Module;
use Contao\NewsModel;
use Contao\StringUtil;

/**
 * @Hook("parseArticles")
 */
class OverrideFeaturedImageSizeListener
{
    private Studio $studio;

    public function __construct(Studio $studio)
    {
        $this->studio = $studio;
    }

    public function __invoke(FrontendTemplate $template, array $newsEntry, Module $module): void
    {
        if (!$newsEntry['featured'] || !$template->addImage) {
            return;
        }

        $size = StringUtil::deserialize($module->imgSize_featured, true);

        if (empty(array_filter($size))) {
            return;
        }

        $news = NewsModel::findById($newsEntry['id']);

        $figureBuilder = $this->studio->createFigureBuilder()
            ->from($news->singleSRC)
            ->setSize($size)
            ->setMetadata($news->getOverwriteMetadata())
            ->enableLightbox((bool) $news->fullsize)
        ;

        // If the external link is opened in a new window, open the image link in a new window as well (see #210)
        if ('external' === $template->source && $template->target) {
            $figureBuilder->setLinkAttribute('target', '_blank');
        }

        if (null !== ($figure = $figureBuilder->buildIfResourceExists())) {
            // Rebuild with link to news article if none is set
            if (!$figure->getLinkHref()) {
                $linkTitle = StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $news->headline), true);

                $figure = $figureBuilder
                    ->setLinkHref($template->link)
                    ->setLinkAttribute('title', $linkTitle)
                    ->build()
                ;
            }

            $figure->applyLegacyTemplateData($template, null, $news->floating);
        }
    }
}
