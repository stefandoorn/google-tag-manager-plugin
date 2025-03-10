<?php

declare(strict_types=1);

namespace Tests\GtmPlugin\Unit\EventListener;

use GtmPlugin\EventListener\EnvironmentListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;

final class EnvironmentListenerTest extends TestCase
{
    public function testEnvironmentIsAddedToGtmObject(): void
    {
        $gtm = new GoogleTagManager(true, 'id1234');
        $listener = new EnvironmentListener(true, $gtm, 'test_env');
        $mock = $this->getMockBuilder(RequestEvent::class)->disableOriginalConstructor()->getMock();
        $mock->method('isMainRequest')->willReturn(true);
        $listener->onKernelRequest($mock);

        self::assertArrayHasKey('env', $gtm->getData());
        self::assertSame($gtm->getData()['env'], 'test_env');
    }
}
