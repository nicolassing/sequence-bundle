<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class HandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('nicolassing_sequence.handler_chain')) {
            return;
        }

        $definition = $container->findDefinition('nicolassing_sequence.handler_chain');
        $taggedServices = $container->findTaggedServiceIds('nicolassing_sequence.handler');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                $definition->addMethodCall('addHandler', array(
                    new Reference($id),
                    $tag['alias']
                ));
            }
        }
    }
}
