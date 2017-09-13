<?php declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

/**
 * Class EnvironmentListener
 * @package SyliusGoogleTagManagerBundle\EventListener
 */
class EnvironmentListener
{
    /**
     * @var GoogleTagManagerInterface
     */
    private $googleTagManager;

    /**
     * @var string
     */
    private $environment;

    /**
     * EnvironmentListener constructor.
     * @param GoogleTagManagerInterface $googleTagManager
     * @param string $environment
     */
    public function __construct(GoogleTagManagerInterface $googleTagManager, string $environment)
    {
        $this->googleTagManager = $googleTagManager;
        $this->environment = $environment;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $this->googleTagManager->setData('env', $this->environment);
    }
}
