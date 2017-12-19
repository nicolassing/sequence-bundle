<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class NicolassingSequenceExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('nicolassing_sequence.model.sequence.class', $config['sequence_class']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        foreach ($config['handlers'] as $name => $handler) {
            $this->buildHandler($container, $name, $handler);
        }
    }

    private function buildHandler(ContainerBuilder $container, $name, array $handler)
    {
        $handlerId = $this->getHandlerId($name);

        if ('service' === $handler['type']) {
            $container->setAlias($handlerId, $handler['id']);

            return $handlerId;
        }

        $definition = new Definition($this->getHandlerClassByType($handler['type']));
        $definition->setPublic(false);
        $definition->addTag('nicolassing_sequence.handler', array('alias' => $name));

        switch ($handler['type']) {
            case 'default':
                $definition->setArguments(array(
                    $handler['prefix'],
                    $handler['length']
                ));
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Invalid handler type "%s" given for handler "%s"', $handler['type'], $name));
        }

        $container->setDefinition($handlerId, $definition);

        return $handlerId;
    }

    private function getHandlerId($name)
    {
        return sprintf('nicolassing_sequence.handler.%s', $name);
    }

    private function getHandlerClassByType($handlerType)
    {
        $typeToClassMapping = array(
            'default' => 'Nicolassing\SequenceBundle\Handler\DefaultHandler',
        );

        if (!isset($typeToClassMapping[$handlerType])) {
            throw new \InvalidArgumentException(sprintf('There is no handler class defined for handler "%s".', $handlerType));
        }

        return $typeToClassMapping[$handlerType];
    }
}
