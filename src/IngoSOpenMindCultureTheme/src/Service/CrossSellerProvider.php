<?php declare(strict_types=1);

namespace IngoSOpenMindCultureTheme\Service;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingLoader;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Content\Product\ProductCollection;

class CrossSellerProvider
{
    public function __construct(private readonly ProductListingLoader $listingLoader) {}

    public function getCrossSellerData(SalesChannelContext $context): ProductCollection
    {
        $criteria = new Criteria();
        $criteria->addAssociation('cover');
        $criteria->addSorting(new FieldSorting('sales', FieldSorting::DESCENDING));
        $criteria->addSorting(new FieldSorting('cheapestPrice.gross', FieldSorting::DESCENDING));
        $criteria->setLimit(3);

        return $this->listingLoader->load($criteria, $context)->getEntities();
    }
}