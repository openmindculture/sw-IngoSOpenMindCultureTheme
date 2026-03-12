<?php declare(strict_types=1);

namespace IngoSOpenMindCultureTheme\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingLoader;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPageLoadedEvent;
use Shopware\Storefront\Page\Checkout\Offcanvas\OffcanvasCartPageLoadedEvent;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class CartCrosssellerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        // private EntityRepository $productRepository
        private readonly ProductListingLoader $listingLoader
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
        $page= $event->getPage();
        $cart = $page->getCart();

        if (!$cart || $cart->getLineItems()->count() > 0) {
            return;
        }

        $criteria = new Criteria();
        $criteria->addAssociation('cover');
        $criteria->addSorting(new FieldSorting('sales', FieldSorting::DESCENDING));
        $criteria->addSorting(new FieldSorting('cheapestPrice.gross', FieldSorting::DESCENDING));
        $criteria->setLimit(3);

        $result = $this->listingLoader->load($criteria, $event->getSalesChannelContext());
        $page->assign(['crossSellers' => $result->getEntities()]);
    }
}