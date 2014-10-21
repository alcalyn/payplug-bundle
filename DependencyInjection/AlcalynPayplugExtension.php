<?php

namespace Alcalyn\PayplugBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AlcalynPayplugExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        // Payplug account parameters
        foreach ($config['account'] as $key => $value) {
            $container->setParameter('payplug.account.'.$key, $value);
        }
        
        // Sandbox parameters
        $this->loadSandboxParameters($config, $container);
        
        // Entities classes to use
        foreach ($config['class'] as $key => $value) {
            $container->setParameter('payplug.class.'.$key, $value);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
    
    /**
     * Load sandbox parameters, or set null if not
     * 
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function loadSandboxParameters(array $config, ContainerBuilder $container)
    {
        $paramPrefix = 'payplug.sandbox.account.';
        
        $container->setParameter($paramPrefix.'url', null);
        $container->setParameter($paramPrefix.'yourPrivateKey', null);
        
        if (isset($config['sandbox']['account'])) {
            $sandbox = $config['sandbox']['account'];
            
            if (isset($sandbox['url'])) {
                $container->setParameter($paramPrefix.'url', $sandbox['url']);
            }
            
            if (isset($sandbox['yourPrivateKey'])) {
                $container->setParameter($paramPrefix.'yourPrivateKey', $sandbox['yourPrivateKey']);
            }
        }
    }
}
