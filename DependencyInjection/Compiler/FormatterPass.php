<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\DependencyInjection\Compiler;

use Nicolassing\SequenceBundle\Formatter\Prefix\PrefixFormatterChain;
use Nicolassing\SequenceBundle\Formatter\Number\NumberFormatterChain;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class FormatterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->addFormatterPass($container,NumberFormatterChain::class, 'nicolassing_sequence.number_formatter');
        $this->addFormatterPass($container, PrefixFormatterChain::class, 'nicolassing_sequence.prefix_formatter');
    }

    private function addFormatterPass(ContainerBuilder $container, $formatterChain, $tag)
    {
        if (!$container->has($formatterChain)) {
            return;
        }

        $definition = $container->findDefinition($formatterChain);
        $taggedServices = $container->findTaggedServiceIds($tag);

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addFormatter', array(
                new Reference($id),
                $id
            ));
        }
    }
}
