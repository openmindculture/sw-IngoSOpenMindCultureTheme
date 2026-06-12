<?php declare(strict_types=1);

namespace IngoSOpenMindCultureTheme\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Shopware\Storefront\Page\Error\ErrorPageLoadedEvent;
use IngoSOpenMindCultureTheme\Service\CrossSellerProvider;

readonly class ErrorPageCrossSellerSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private CrossSellerProvider $crossSellerProvider
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            ErrorPageLoadedEvent::class => 'onErrorPageLoaded',
        ];
    }

    public function onErrorPageLoaded(ErrorPageLoadedEvent $event): void
    {

        $exception = $event->getException();
        if (!$exception instanceof NotFoundHttpException) {
            return;
        }

        $page= $event->getPage();
        $page->assign(['crossSellers' => $this->crossSellerProvider->getCrossSellerData($event->getSalesChannelContext())]);
    }
}