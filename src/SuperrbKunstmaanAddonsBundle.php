<?php

namespace Superrb\MemcachedHandlerBundle;

use Superrb\KunstmaanAddonsBundle\DependencyInjection\SuperrbKunstmaanAddonsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MemcachedHandlerBundle
 * @package Superrb\MemcachedHandlerBundle
 */
class SuperrbKunstmaanAddonsBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->registerExtension(new SuperrbKunstmaanAddonsExtension());
    }
}