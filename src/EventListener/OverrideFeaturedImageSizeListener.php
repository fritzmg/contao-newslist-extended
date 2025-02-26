<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoNewslistExtended\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\FrontendTemplate;
use Contao\Module;
use Contao\NewsModel;
use Contao\StringUtil;

#[AsHook('parseArticles')]
class OverrideFeaturedImageSizeListener
{
    public function __construct(private readonly Studio $studio)
    {
    }

    public function __invoke(FrontendTemplate $template, array $newsEntry, Module $module): void
    {
        if (!$newsEntry['featured'] || !$template->addImage) {
            return;
        }

        $size = StringUtil::deserialize($module->imgSize_featured, true);

        if ([] === array_filter($size)) {
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

        if ($figure = $figureBuilder->buildIfResourceExists()) {
            // Rebuild with link to news article if none is set
            if (!$figure->getLinkHref()) {
                $linkTitle = StringUtil::specialchars(\sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $news->headline), true);

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
