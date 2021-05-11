<?php

namespace Tests\GtmPlugin\EventListener;

use GtmPlugin\EventListener\EnvironmentListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;

/**
 * Class EnvironmentListenerTest
 * @package Tests\GtmPlugin\EventListener
 * @covers \GtmPlugin\EventListener\EnvironmentListener
 */
class EnvironmentListenerTest extends TestCase
{

    public function testEnvironmentIsAddedToGtmObject()
    {
        $gtm = new GoogleTagManager(true, 'id1234');
        $listener = new EnvironmentListener($gtm, 'test_env');
        $mock = $this->getMockBuilder(RequestEvent::class)->disableOriginalConstructor()->getMock();
        $listener->onKernelRequest($mock);

        $this->assertArrayHasKey('env', $gtm->getData());
        $this->assertSame($gtm->getData()['env'], 'test_env');
    }
}
