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

use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\Routing\ResponseContext\ResponseContextAccessor;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\FrontendTemplate;
use Contao\Module;
use Contao\News;
use Contao\NewsModel;
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Overrides the news link if applicable.
 *
 * @Hook("parseArticles", priority=10)
 */
class OverrideNewsUrlListener
{
    private RequestStack $requestStack;
    private ResponseContextAccessor $responseContextAccessor;

    public function __construct(RequestStack $requestStack, ResponseContextAccessor $responseContextAccessor)
    {
        $this->requestStack = $requestStack;
        $this->responseContextAccessor = $responseContextAccessor;
    }

    public function __invoke(FrontendTemplate $template, array $newsEntry, Module $module): void
    {
        // check if the source is "default"
        if ('default' !== $newsEntry['source']) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();

        // check if this is the newsreader
        if ('newsreader' === $module->type) {
            // get the current uri
            $currentUri = $request->getUri();

            // get the canonical uri
            $canonicalUri = News::generateNewsUrl(NewsModel::findById($newsEntry['id']), false, true);

            // check if Uris are the same
            if ($currentUri !== $canonicalUri) {
                $addCanonical = true;

                if ($GLOBALS['objPage']->enableCanonical ?? false) {
                    $responseContext = $this->responseContextAccessor->getResponseContext();

                    if ($responseContext->has(HtmlHeadBag::class)) {
                        /** @var HtmlHeadBag $htmlHeadBag */
                        $htmlHeadBag = $responseContext->get(HtmlHeadBag::class);
                        $htmlHeadBag->setCanonicalUri($canonicalUri);
                        $addCanonical = false;
                    }
                }

                if ($addCanonical) {
                    // insert canonical tag
                    $GLOBALS['TL_HEAD'][] = '<link rel="canonical" href="'.$canonicalUri.'">';
                }
            }

            // don't do anything else
            return;
        }

        // check if a custom redirect page is set and the article has no redirects on its own
        if ('default' === $newsEntry['source']
         && $module->news_overrideRedirect
         && $module->jumpTo
         && null !== ($target = PageModel::findById($module->jumpTo))) {
            // build the href
            $href = $target->getFrontendUrl('/'.$newsEntry['alias']);

            // encode href
            $href = StringUtil::ampersand($href);

            // update links
            if (!$newsEntry['fullsize']) {
                $template->href = $href;
                $template->imageHref = $href;
            }

            $template->link = $href;
            $template->linkHeadline = $this->generateNewsLink($href, $newsEntry['headline'], $newsEntry['headline']);
            $template->more = $this->generateNewsLink($href, $newsEntry['headline'], $GLOBALS['TL_LANG']['MSC']['more'], true);
        }
    }

    /**
     * Generates a HTML link for a news entry.
     *
     * @param string $href
     * @param string $title
     * @param string $text
     *
     * @return string
     */
    private function generateNewsLink($href, $title, $text, $isReadMore = false)
    {
        return sprintf('<a href="%s" title="%s" itemprop="url">%s%s</a>',
            $href,
            StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $title), true),
            $text,
            $isReadMore ? ' <span class="invisible">'.$title.'</span>' : '');
    }
}
