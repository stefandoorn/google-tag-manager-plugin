<?php declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

/**
 * Class AddRouteListener
 * @package GtmPlugin\EventListener
 */
final class AddRouteListener
{
    /**
     * @var GoogleTagManagerInterface
     */
    private $googleTagManager;

    /**
     * AddRouteListener constructor.
     * @param GoogleTagManagerInterface $googleTagManager
     */
    public function __construct(GoogleTagManagerInterface $googleTagManager)
    {
        $this->googleTagManager = $googleTagManager;
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        // Only perform on master request
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request) {
            return;
        }

        $this->googleTagManager->setData('route', $request->get('_route'));
    }
}
