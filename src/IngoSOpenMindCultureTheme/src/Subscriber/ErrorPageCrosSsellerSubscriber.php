<?php declare(strict_types=1);

namespace IngoSOpenMindCultureTheme\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\PageLoadedEvent;
use IngoSOpenMindCultureTheme\Service\CrossSellerProvider;

readonly class ErrorPageCrossSellerSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly CrossSellerProvider $crossSellerProvider
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            GenericPageLoadedEvent::class => 'onErrorPageLoaded',
        ];
    }

    public function onErrorPageLoaded(GenericPageLoadedEvent $event): void
    {
        /** @var \Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPage $page */
        $page= $event->getPage();
        
        $request = $event->getRequest();

        $exception = $request->attributes->get('exception')
            ?? $request->attributes->get('_exception');

        $statusCode = $request->attributes->get('_http_status_code');

        if (
            !$exception instanceof NotFoundHttpException
            && $statusCode !== Response::HTTP_NOT_FOUND
        ) {
            return;
        }

        $page->assign(['crossSellers' => $this->crossSellerProvider->getCrossSellerData($event->getSalesChannelContext())]);
    }
}