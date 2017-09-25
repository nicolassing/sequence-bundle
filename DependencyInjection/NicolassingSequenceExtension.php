<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\DependencyInjection;

use Nicolassing\SequenceBundle\Formatter\Number\Factory\NumberFormatterFactoryInterface;
use Nicolassing\SequenceBundle\Formatter\Number\NumberFormatterInterface;
use Nicolassing\SequenceBundle\Formatter\Number\PrefixFormatterInterface;
use Nicolassing\SequenceBundle\Formatter\Prefix\Factory\PrefixFormatterFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
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

        $this->loadNumberFormatters($container, $config);
        $this->loadPrefixFormatters($container, $config);
    }

    private function loadNumberFormatters(ContainerBuilder $container, array $config)
    {
        foreach ($config['number_formatters'] as $formatterName => $formatterConfig) {
            $factoryService = $container->getDefinition($formatterConfig['factory']);
            $factoryClass = $factoryService->getClass() ?: $formatterConfig['factory'];
            if (!$this->implementsInterface(NumberFormatterFactoryInterface::class, $factoryClass)) {
                throw new \LogicException(sprintf('Number formatter factory "%s" must implement %s', $formatterConfig['factory'], NumberFormatterFactoryInterface::class));
            }

            $serviceId = 'nicolassing_sequence.number_formatter.'.$formatterName;
            $container->register($serviceId, NumberFormatterInterface::class)
                ->setFactory([$factoryClass, 'createFormatter'])
                ->addArgument($formatterConfig['options'])
                ->addTag('nicolassing_sequence.number_formatter');
        }
    }

    private function loadPrefixFormatters(ContainerBuilder $container, array $config)
    {
        foreach ($config['prefix_formatters'] as $formatterName => $formatterConfig) {
            $factoryService = $container->getDefinition($formatterConfig['factory']);
            $factoryClass = $factoryService->getClass() ?: $formatterConfig['factory'];
            if (!$this->implementsInterface(PrefixFormatterFactoryInterface::class, $factoryClass)) {
                throw new \LogicException(sprintf('Prefix formatter factory "%s" must implement %s', $formatterConfig['factory'], NumberFormatterFactoryInterface::class));
            }

            $serviceId = 'nicolassing_sequence.prefix_formatter.'.$formatterName;
            $definition = $container->register($serviceId, PrefixFormatterInterface::class)
                ->setFactory([$factoryClass, 'createFormatter'])
                ->addArgument($formatterConfig['options']);
            $definition->addTag('nicolassing_sequence.prefix_formatter');
        }
    }

    /**
     * @param $interface
     * @param $class
     *
     * @return bool
     */
    private function implementsInterface($interface, $class): bool
    {
        if (false === $interfaces = class_implements($class)) {
            return false;
        }
        return in_array($interface, $interfaces);
    }
}
