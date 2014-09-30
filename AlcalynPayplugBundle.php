<?php

namespace Alcalyn\PayplugBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Alcalyn\PayplugBundle\DependencyInjection\Compiler\RegisterMappingsPass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass;
use Doctrine\Bundle\CouchDBBundle\DependencyInjection\Compiler\DoctrineCouchDBMappingsPass;

class AlcalynPayplugBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $this->registerDoctrineMapping($container);
    }
    
    /**
     * Import doctrine mapping located in Resources/config/doctrine/model
     * 
     * @param ContainerBuilder $container
     */
    private function registerDoctrineMapping(ContainerBuilder $container)
    {
        $modelDir = realpath(__DIR__.'/Resources/config/doctrine/model');
        $mappings = array(
            $modelDir => 'Alcalyn\PayplugBundle\Model',
        );

        $ormCompilerClass = 'Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass';
        if (class_exists($ormCompilerClass)) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createYamlMappingDriver($mappings));
        } else {
            $container->addCompilerPass(RegisterMappingsPass::createOrmMappingDriver($mappings));
        }
    }
}
