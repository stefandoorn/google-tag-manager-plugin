<?php

namespace Tests\GtmPlugin\EventListener;

use GtmPlugin\EventListener\EnvironmentListener;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;

/**
 * Class EnvironmentListenerTest
 * @package Tests\GtmPlugin\EventListener
 * @covers \GtmPlugin\EventListener\EnvironmentListener
 */
class EnvironmentListenerTest extends \PHPUnit_Framework_TestCase
{

    public function testEnvironmentIsAddedToGtmObject()
    {
        $gtm = new GoogleTagManager(true, 'id1234');
        $listener = new EnvironmentListener(true, $gtm, 'test_env');
        $mock = $this->getMockBuilder(GetResponseEvent::class)->disableOriginalConstructor()->getMock();
        $listener->onKernelRequest($mock);

        $this->assertArrayHasKey('env', $gtm->getData());
        $this->assertSame($gtm->getData()['env'], 'test_env');
    }
}
