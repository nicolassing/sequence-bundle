<?php

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
                ->scalarNode('model_manager_name')->defaultNull()->end()
                ->append($this->getProvidersNode())
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function getProvidersNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('types');
        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('prefix')->defaultNull()->end()
                    ->scalarNode('prefix_formatter')->defaultNull()->end()
                    ->scalarNode('number_formatter')->defaultNull()->end()
                    ->scalarNode('number_length')->defaultNull()->end()
                ->end()
            ->end();
        return $node;
    }
}
