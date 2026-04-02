<?php declare(strict_types=1);

namespace IngoSOpenMindCultureTheme\Subscriber;

use Shopware\Core\Content\Product\Events\ProductListingCriteriaEvent;
use Shopware\Core\Content\Product\Events\ProductSearchCriteriaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductListingCategorySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ProductListingCriteriaEvent::class => 'onProductListingCriteria',
            ProductSearchCriteriaEvent::class => 'onProductListingCriteria',
        ];
    }

    public function onProductListingCriteria(ProductListingCriteriaEvent $event): void
    {
        $event->getCriteria()->addAssociation('categories');
    }
}