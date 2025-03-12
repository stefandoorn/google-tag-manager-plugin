<?php

declare(strict_types=1);

namespace Tests\GtmPlugin\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RenderTemplateTest extends WebTestCase
{
    public function test_homepage_render_templates(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $client->followRedirect();

        self::assertResponseIsSuccessful();

        self::assertSelectorExists('[data-test-gtm-after-body]');
        self::assertSelectorExists('[data-test-gtm-body]');
        self::assertSelectorExists('[data-test-gtm-events-javascript]');
        self::assertSelectorExists('[data-test-gtm-head]');
    }

    public function test_admin_render_templates(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin');

        $client->followRedirect();

        self::assertResponseIsSuccessful();

        self::assertSelectorNotExists('[data-test-gtm-after-body]');
        self::assertSelectorNotExists('[data-test-gtm-body]');
        self::assertSelectorNotExists('[data-test-gtm-events-javascript]');
        self::assertSelectorNotExists('[data-test-gtm-head]');
    }
}
