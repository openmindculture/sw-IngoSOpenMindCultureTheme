<?php declare(strict_types=1);

namespace IngoSOpenMindCultureTheme\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\PageLoadedEvent;
use IngoSOpenMindCultureTheme\Service\CrossSellerProvider;

readonly class SearchPageCrossSellerSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly CrossSellerProvider $crossSellerProvider
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ErrorPageLoadedEvent::class         => 'onPageLoaded',
            ProductSearchPageLoadedEvent::class => 'onPageLoaded',
        ];
    }

    public function onCartLoaded(PageLoadedEvent $event): void
    {
        /** @var \Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPage $page */
        $page= $event->getPage();
        
        if (false) { // TODO is 404 page or empty search result
            return;
        }

        $page->assign(['crossSellers' => $this->loadCrossSellers($event->getSalesChannelContext())]);
    }
}