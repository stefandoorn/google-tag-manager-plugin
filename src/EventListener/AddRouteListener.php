<?php

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

/**
 * Class AddRouteListener
 * @package SyliusGoogleTagManagerBundle\EventListener
 */
class AddRouteListener
{

    /**
     * @var GoogleTagManager
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
        $this->googleTagManager->addData('route', $this->request->getCurrentRequest()->get('_route'));
    }
}
