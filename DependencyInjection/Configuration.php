<?php

namespace Alcalyn\PayplugBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Alcalyn\PayplugBundle\Model\Payment;
use Alcalyn\PayplugBundle\Model\IPN;

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
        $rootNode = $treeBuilder->root('alcalyn_payplug');
        
        $rootNode
            ->children()
            ->arrayNode('account')
                ->children()
                    ->scalarNode('url')->end()
                    ->scalarNode('amount_min')->end()
                    ->scalarNode('amount_max')->end()
                    ->arrayNode('currencies')
                        ->prototype('scalar')->end()
                    ->end()
                    ->scalarNode('payplugPublicKey')->end()
                    ->scalarNode('yourPrivateKey')->end()
                ->end()
            ->end()
            ->arrayNode('class')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('ipn')->defaultValue(IPN::getClassName())->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
