<?php declare(strict_types=1);

namespace IngoSOpenMindCultureTheme\Subscriber;

use Shopware\Core\Content\Category\Service\NavigationLoader;
use Shopware\Storefront\Page\GenericPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class FooterNavigationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private NavigationLoader $navigationLoader
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            GenericPageLoadedEvent::class => 'onPageLoaded',
        ];
    }

    public function onPageLoaded(GenericPageLoadedEvent $event): void
    {
        $context = $event->getSalesChannelContext();
        $page    = $event->getPage();

        $salesChannel = $context->getSalesChannel();

        // Use footer nav root if configured, fall back to product root
        $rootId = $salesChannel->getFooterCategoryId()
            ?? $salesChannel->getNavigationCategoryId();

        if (!$rootId) {
            return;
        }

        // Load the navigation tree (depth 2 is typical for footers)
        $tree = $this->navigationLoader->load(
            $rootId,
            $context,
            $rootId,
            4
        );
        // dump($tree); // Check Symfony debug toolbar
        // Inject into page extensions so Twig can access it
        $page->addExtension('footerCategories', $tree);
    }
}