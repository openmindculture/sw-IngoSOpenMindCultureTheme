<?php declare(strict_types=1);

namespace IngoSOpenMindCultureTheme\Subscriber;

use Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\PageLoadedEvent;
use Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPageLoadedEvent;
use Shopware\Storefront\Page\Checkout\Offcanvas\OffcanvasCartPageLoadedEvent;
use IngoSOpenMindCultureTheme\Service\CrossSellerProvider;

readonly class CartCrossSellerSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private CrossSellerProvider $crossSellerProvider
    ) {}


    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutCartPageLoadedEvent::class  => 'onCartLoaded',
            OffcanvasCartPageLoadedEvent::class => 'onCartLoaded',
        ];
    }

    public function onCartLoaded(PageLoadedEvent $event): void
    {
        /** @var CheckoutCartPage $page */
        $page= $event->getPage();
        $cart = $page->getCart();

        if ($cart->getLineItems()->count() > 0) {
            return;
        }

        $page->assign(['crossSellers' => $this->crossSellerProvider->getCrossSellerData($event->getSalesChannelContext())]);
    }
}