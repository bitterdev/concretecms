<?php

/* Author: Fabian Bitter (fabian@bitter.de) */

/*
 * WHY IT SITS IN bootstrap AND NOT IN application/src. application/src of this
 * installation is excluded from version control in full (application/src/.gitignore),
 * so a class put there would live on this disk only, which is the very thing that
 * happened to the JUNO-802 fix on the live host. Required by hand next to
 * dev-storage-guard.php, the same way that guard is wired in.
 *
 * THE NAMESPACE IS THE ONE THE LIVE INSTALLATION USES, so the body of this file goes
 * there unchanged. Read off that host on 30.08.2026, so the two steps are known and
 * not guessed:
 *   1. the class goes to /bamg.ae/httpdocs/application/src/Provider/ under the name
 *      SystemPageSiteTree.php, because application/src is autoloaded PSR-4 and the
 *      file has to carry the class name there;
 *   2. in that directory's ServiceProvider.php the line
 *      $this->restoreSiteTreeOnNotFoundPage(); in register() becomes
 *      $this->app->make(SystemPageSiteTree::class)->register();
 *      and the old method goes away. It compared one path, /page_not_found, and took
 *      the tree of the site's DEFAULT locale.
 * application/src is outside version control on both installations, so a push alone
 * does not carry this over: the file has to be put there.
 */

namespace Application\Provider;

use Concrete\Core\Entity\Site\Locale;
use Concrete\Core\Entity\Site\Site;
use Concrete\Core\Entity\Site\Tree;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Http\Request;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\View\PageView;
use Concrete\Core\Site\Service as SiteService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Gives a page that carries no site tree the tree of the request it is answering.
 *
 * WHAT IS BROKEN. A handful of pages are installed once for the whole installation
 * and live outside every site tree: Pages.siteTreeID is 0 for /page_not_found,
 * /page_forbidden, /login, /register and everything under /account. Whatever asks
 * such a page which site it belongs to gets nothing, and Page::getHomePageID then
 * falls back to the DEFAULT locale of the DEFAULT site. Themes build their logo link
 * and the root of their navigation out of exactly that value, see
 * bitter_theme/elements/header.php: $homePage = Page::getByID(Page::getHomePageID(
 * Page::getCurrentPage())). On the live multisite the default site is bamg.ae, so a
 * visitor who was refused on freshcast.de was handed the menu and the logo of a
 * different company. Fabian, 29.08.2026: "Da ist die Navigation und das Logo von
 * BAMG gerendert. Das darf auf keinen Fall passieren."
 *
 * WHY THERE IS NO LIST OF PATHS HERE. The first two attempts at this named the
 * pages: JUNO-802 compared against '/page_not_found', JUNO-1405 then made that a
 * two entry constant. Both are the same mistake one size up. A named page has to be
 * remembered on the day a new system page starts to matter, and nobody notices the
 * omission until a visitor sees the wrong brand. Fabian, 29.08.2026: "Wieso prueft
 * man nicht einfach in einem on_start-Handler in einem Service Provider nach der
 * Adresse und vor allem nach der Seite ... Best Practices will ich haben bei
 * Concrete CMS." So the question asked here is a property of the page, not its
 * name: does this page know which house it is standing in? If it does not, and it
 * is the page this request is being answered with, it is handed the house the
 * request was made to. /login, /register, /account and every system page added
 * later are covered by that without another line.
 *
 * WHERE IT HANGS. on_start of the view, dispatched by View::startRender() with the
 * view as its argument, which is the earliest point at which the page object being
 * drawn is in hand and still the earliest point before the theme writes a single
 * link. It is deliberately NOT a listener on something every request touches: the
 * view is asked for its page and everything that is not a page view is left alone,
 * so the /ccm/ routes that broke the whole installation on 19.08.2026 never reach
 * this code.
 */
class SystemPageSiteTree extends Provider
{
    public function register()
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->app->make(EventDispatcherInterface::class);

        $eventDispatcher->addListener('on_start', function (GenericEvent $event) {
            $this->giveBackTheTreeOfTheRequest($event->getArgument('view'));
        });
    }

    /**
     * @param mixed $view whatever is being rendered, page view or not
     */
    private function giveBackTheTreeOfTheRequest($view): void
    {
        if (!$view instanceof PageView) {
            return;
        }

        $page = $view->getPageObject();

        if (!$page instanceof Page || $page->isError()) {
            return;
        }

        /*
         * A page that has a tree is standing in its own house already, and the one
         * thing that must not happen here is moving a page that is fine.
         */
        if ($page->getSiteTreeID()) {
            return;
        }

        /*
         * Only the page the request is actually being answered with. A stack, a theme
         * preview or anything else drawn inside a page carries no tree either, and
         * those are not what a visitor reads the brand off. ResponseFactory sets this
         * for the error pages and the dispatcher for every other page.
         */
        $currentPage = $this->app->make(Request::class)->getCurrentPage();

        if (!$currentPage instanceof Page || $currentPage->getCollectionID() != $page->getCollectionID()) {
            return;
        }

        /*
         * The dashboard is left alone on purpose. It brings its own chrome, it has no
         * logo taken from a site tree, and it answers the question which site it is
         * working on with getActiveSiteForEditing(), which is the editor's choice and
         * not the domain that was typed. Handing it a tree would be a change to the
         * back end nobody asked for.
         */
        if ($page->isAdminArea()) {
            return;
        }

        $tree = $this->treeOfTheRequest();

        if ($tree instanceof Tree) {
            $page->siteTree = $tree;
        }
    }

    /**
     * The tree the request belongs to: the site the address was made to, in the
     * language the answer is being written in.
     *
     * The site comes from Concrete's own resolver, which reads it off the domain of
     * the request (Site\Resolver\MultisiteDriver), so it is the site that was asked
     * for and not the default one. The language comes from the site localization
     * context, which the multilingual detector fills in from the address before the
     * controller runs (Detector::setupSiteInterfaceLocalization). Taking the default
     * locale instead, as JUNO-802 did, names the right company but the wrong language
     * tree, and a Spanish visitor kept getting the German menu.
     */
    private function treeOfTheRequest(): ?Tree
    {
        /** @var SiteService $siteService */
        $siteService = $this->app->make(SiteService::class);
        $site = $siteService->getSite();

        if (!$site instanceof Site) {
            return null;
        }

        /** @var Localization $localization */
        $localization = $this->app->make(Localization::class);
        $locale = $localization->getContextLocale(Localization::CONTEXT_SITE);

        if (!$locale) {
            $locale = Localization::activeLocale();
        }

        foreach ($site->getLocales() as $candidate) {
            if (!$candidate instanceof Locale || $candidate->getLocale() !== $locale) {
                continue;
            }

            $tree = $candidate->getSiteTreeObject();

            if ($tree instanceof Tree) {
                return $tree;
            }
        }

        $defaultLocale = $site->getDefaultLocale();

        if (!$defaultLocale instanceof Locale) {
            return null;
        }

        $tree = $defaultLocale->getSiteTreeObject();

        return $tree instanceof Tree ? $tree : null;
    }
}
