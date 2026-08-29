<?php

/* Author: Fabian Bitter (fabian@bitter.de) */

/*
 * WHY IT SITS IN bootstrap AND NOT IN application/src. application/src of this
 * installation is excluded from version control in full (application/src/.gitignore),
 * so a class put there would live on this disk only, which is the very thing that
 * happened to the JUNO-802 fix on the live host. Required by hand next to
 * dev-storage-guard.php, the same way that guard is wired in.
 *
 * THE NAMESPACE IS THE ONE THE LIVE INSTALLATION USES, so this file goes there
 * unchanged: /bamg.ae/httpdocs/application/src/Provider/, where its register() is
 * called from ServiceProvider::register() and replaces
 * restoreSiteTreeOnNotFoundPage(), which covered only the not found half.
 */

namespace Application\Provider;

use Concrete\Core\Entity\Site\Locale;
use Concrete\Core\Entity\Site\Site;
use Concrete\Core\Entity\Site\Tree;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Page\Page;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/*
 * The core error pages are installation wide system pages and carry no site tree
 * (Pages.siteTreeID = 0). Everything that asks the current page for its site
 * therefore falls back to the default site, see Page::getHomePageID(). On a
 * multisite install an error on any site consequently rendered the default site's
 * home page id, which the autonav in the global header area uses as its root and
 * which the themes use for the logo link: a visitor of freshcast.de ended up with
 * the menu and the logo of bamg.ae. Attaching the tree that was actually asked for
 * restores the context before the theme is rendered.
 *
 * This is the fix JUNO-802 put on /page_not_found, now written once for both error
 * pages. Fabian, 29.08.2026: "Das gleiche gibt es mit 403. Da ist die Navigation und
 * das Logo von BAMG gerendert. Das darf auf keinen Fall passieren."
 */
class SystemPageSiteTree extends Provider
{
    /**
     * The system pages drawn in place of the page that was asked for. Deliberately
     * a short list: /login and /register carry siteTreeID 0 as well, but they are
     * not error pages and changing them is a decision of its own.
     */
    private const SYSTEM_PAGES = [
        '/page_not_found',
        '/page_forbidden',
    ];

    public function register()
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->app->make(EventDispatcherInterface::class);

        $eventDispatcher->addListener('on_before_render', function () {
            $c = Page::getCurrentPage();

            if (!$c instanceof Page || $c->isError() || $c->getSiteTreeID()) {
                return;
            }

            if (!in_array($c->getCollectionPath(), self::SYSTEM_PAGES, true)) {
                return;
            }

            $tree = $this->treeOfActiveContext();

            if ($tree instanceof Tree) {
                $c->siteTree = $tree;
            }
        });
    }

    /**
     * The tree of the language the request is being answered in, and the tree of the
     * site's default locale where that cannot be told. The default locale alone is
     * what JUNO-802 used; it names the right site but not the right language tree, so
     * a Spanish visitor of a forbidden page kept getting the German menu of the same
     * site.
     */
    private function treeOfActiveContext(): ?Tree
    {
        $site = $this->app->make('site')->getSite();

        if (!$site instanceof Site) {
            return null;
        }

        $activeLocale = Localization::activeLocale();

        foreach ($site->getLocales() as $locale) {
            if (!$locale instanceof Locale || $locale->getLocale() !== $activeLocale) {
                continue;
            }

            $tree = $locale->getSiteTreeObject();

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
