<?php

namespace Swm\VideotekBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('swm_videotek');

        $this->addpath($rootNode);

        return $treeBuilder;
    }

    /**
     * Add Configuration Captcha
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addPath(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('path')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('thumbnails')->defaultValue('thumbnails')->end()
                        ->variableNode('test')->defaultValue('test')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
