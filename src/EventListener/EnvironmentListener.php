<?php declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class EnvironmentListener
{
    /**
     * @var GoogleTagManagerInterface
     */
    private $googleTagManager;

    /**
     * @var string
     */
    private $environment;

    public function __construct(GoogleTagManagerInterface $googleTagManager, string $environment)
    {
        $this->googleTagManager = $googleTagManager;
        $this->environment = $environment;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->googleTagManager->setData('env', $this->environment);
    }
}
