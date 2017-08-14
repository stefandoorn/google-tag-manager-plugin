<?php

namespace Tests\GtmPlugin\EventListener;

use GtmPlugin\EventListener\AddRouteListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;

/**
 * Class AddRouteListenerTest
 * @package Tests\GtmPlugin\EventListener
 * @covers \GtmPlugin\EventListener\AddRouteListener
 */
class AddRouteListenerTest extends \PHPUnit_Framework_TestCase
{

    public function testAddRouteIsAddedToGtmObject()
    {
        $request = new Request(['_route' => 'test_route']);
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $gtm = new GoogleTagManager(true, 'id1234');
        $listener = new AddRouteListener($gtm, $requestStack);
        $mock = $this->getMockBuilder(GetResponseEvent::class)->disableOriginalConstructor()->getMock();
        $listener->onKernelRequest($mock);

        $this->assertArrayHasKey('route', $gtm->getData());
        $this->assertSame($gtm->getData()['route'], 'test_route');
    }
}
