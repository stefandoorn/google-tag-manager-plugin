<?php declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
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
     * @var RequestStack
     */
    private $request;

    /**
     * AddRouteListener constructor.
     * @param GoogleTagManagerInterface $googleTagManager
     * @param RequestStack $request
     */
    public function __construct(GoogleTagManagerInterface $googleTagManager, RequestStack $request)
    {
        $this->googleTagManager = $googleTagManager;
        $this->request = $request;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $this->request->getCurrentRequest();

        if (!$request) {
            return;
        }

        $this->googleTagManager->setData('route', $request->get('_route'));
    }
}
