<?php

namespace Alcalyn\PayplugBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

class RegisterMappingsPass implements CompilerPassInterface
{
    private $driver;
    private $driverPattern;
    private $namespaces;
    private $managerParameters;
    private $enabledParameter;

    public function __construct($driver, $driverPattern, $namespaces, $managerParameters, $enabledParameter = false)
    {
        $this->driver = $driver;
        $this->driverPattern = $driverPattern;
        $this->namespaces = $namespaces;
        $this->managerParameters = $managerParameters;
        $this->enabledParameter = $enabledParameter;
    }

    public function process(ContainerBuilder $container)
    {
        if ($this->enabledParameter && !$container->hasParameter($this->enabledParameter)) {
            return;
        }

        $chainDriverDefService = $this->getChainDriverServiceName($container);
        $chainDriverDef = $container->getDefinition($chainDriverDefService);
        foreach ($this->namespaces as $namespace) {
            $chainDriverDef->addMethodCall('addDriver', array($this->driver, $namespace));
        }
    }

    protected function getChainDriverServiceName(ContainerBuilder $container)
    {
        foreach ($this->managerParameters as $param) {
            if ($container->hasParameter($param)) {
                $name = $container->getParameter($param);
                if ($name) {
                    return sprintf($this->driverPattern, $name);
                }
            }
        }

        throw new ParameterNotFoundException('None of the managerParameters resulted in a valid name');
    }

    public static function createOrmMappingDriver(array $mappings)
    {
        $arguments = array($mappings, '.orm.yml');
        $locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', $arguments);
        $driver = new Definition('Doctrine\ORM\Mapping\Driver\YamlDriver', array($locator));
        $driverPattern = 'doctrine.orm.%s_metadata_driver';
        $managerParameters = array('doctrine.default_entity_manager');

        return new RegisterMappingsPass($driver, $driverPattern, $mappings, $managerParameters);
    }
}