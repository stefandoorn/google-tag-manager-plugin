<?php

declare(strict_types=1);

namespace Tests\GtmPlugin\EventListener;

use GtmPlugin\EventListener\AddRouteListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;

final class AddRouteListenerTest extends TestCase
{
    public function testAddRouteIsAddedToGtmObject()
    {
        $request = new Request(['_route' => 'test_route']);

        $gtm = new GoogleTagManager(true, 'id1234');
        $listener = new AddRouteListener($gtm);

        $mock = $this->getMockBuilder(RequestEvent::class)->disableOriginalConstructor()->getMock();

        $mock
            ->expects($this->once())
            ->method('isMasterRequest')
            ->willReturn(true);

        $mock
            ->expects($this->once())
            ->method('getRequest')
            ->willReturn($request);

        $listener->onKernelRequest($mock);

        $this->assertArrayHasKey('route', $gtm->getData());
        $this->assertSame($gtm->getData()['route'], 'test_route');
    }
}
