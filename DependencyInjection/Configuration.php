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
                ->append($this->getHandlersNode())
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function getHandlersNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('handlers');
        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('type')
                        ->isRequired()
                        ->treatNullLike('null')
                        ->beforeNormalization()
                            ->always()
                            ->then(function ($v) {
                                return strtolower($v);
                            })
                        ->end()
                    ->end()
                    ->scalarNode('id')->end() // service
                    ->integerNode('length')->defaultValue(9)->end() // default
                    ->scalarNode('prefix')->defaultNull()->end() // default
                ->end()
                ->validate()
                    ->ifTrue(function ($v) {
                        return 'service' === $v['type'] && empty($v['id']);
                    })
                    ->thenInvalid('If you use service handler you must provide and id.')
                ->end()
                ->example(array(
                    'default' => array(
                        'type' => 'default',
                        'length' => '3',
                        'prefix' => 'NA',
                        ),
                    'invoice' => array(
                        'type' => 'service',
                        'id' => 'my_handler',
                        ),
                    ))
            ->end();
        return $node;
    }
}
