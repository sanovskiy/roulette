<?php

namespace App\DependencyInjection\Statistic\CompilerPass;

use App\Statistic\StatisticContext;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LoadStatisticMethodsCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $context = $container->findDefinition(StatisticContext::class);
        $taggedServices = $container->findTaggedServiceIds('statistic.method');
        $taggedServiceIds = array_keys($taggedServices);
        foreach ($taggedServiceIds as $taggedServiceId) {
            $context->addMethodCall('addProvider', [new Reference($taggedServiceId)]);
        }
    }
}
