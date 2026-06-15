<?php declare(strict_types=1);

namespace IngoSOpenMindCultureTheme\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\Search\SearchPageLoadedEvent;
use IngoSOpenMindCultureTheme\Service\CrossSellerProvider;

readonly class SearchPageCrossSellerSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private CrossSellerProvider $crossSellerProvider
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            SearchPageLoadedEvent::class => 'onPageLoaded',
        ];
    }

    public function onPageLoaded(SearchPageLoadedEvent $event): void
    {
        $page= $event->getPage();
        $page->assign(['crossSellers' => $this->crossSellerProvider->getCrossSellerData($event->getSalesChannelContext())]);
    }
}