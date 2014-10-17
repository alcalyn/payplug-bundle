<?php

namespace Alcalyn\PayplugBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

class RegisterMappingsPass implements CompilerPassInterface
{
    /**
     * @var Definition|Reference
     */
    private $driver;
    
    /**
     * @var string
     */
    private $driverPattern;
    
    /**
     * @var array
     */
    private $namespaces;
    
    /**
     * @var array
     */
    private $managerParameters;
    
    /**
     * @var mixed false or parameter name
     */
    private $enabledParameter;

    /**
     * @param Definition $driver
     * @param string $driverPattern
     * @param array $namespaces
     * @param string[] $managerParameters
     * @param mixed $enabledParameter
     */
    public function __construct($driver, $driverPattern, $namespaces, $managerParameters, $enabledParameter = false)
    {
        $this->driver = $driver;
        $this->driverPattern = $driverPattern;
        $this->namespaces = $namespaces;
        $this->managerParameters = $managerParameters;
        $this->enabledParameter = $enabledParameter;
    }

    /**
     * @param ContainerBuilder $container
     */
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

    /**
     * @param ContainerBuilder $container
     * 
     * @return string
     * 
     * @throws ParameterNotFoundException
     */
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

    /**
     * @param array $mappings
     * 
     * @return \Alcalyn\PayplugBundle\DependencyInjection\Compiler\RegisterMappingsPass
     */
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
