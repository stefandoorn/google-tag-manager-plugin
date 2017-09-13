<?php declare(strict_types=1);

namespace GtmPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class GtmPlugin
 * @package GtmPlugin
 */
final class GtmPlugin extends Bundle
{
    use SyliusPluginTrait;
}
