<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nicolassing_sequence');

        $rootNode
            ->children()
                ->scalarNode('sequence_class')->isRequired()->cannotBeEmpty()->end()
                ->append($this->getNumberFormattersNode())
                ->append($this->getPrefixFormattersNode())
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function getNumberFormattersNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('number_formatters');
        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('factory')->isRequired()->cannotBeEmpty()->end()
                    ->variableNode('options')->defaultValue([])->end()
                ->end()
            ->end();
        return $node;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function getPrefixFormattersNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('prefix_formatters');
        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('factory')->isRequired()->cannotBeEmpty()->end()
                    ->variableNode('options')->defaultValue([])->end()
                ->end()
            ->end();
        return $node;
    }
}
