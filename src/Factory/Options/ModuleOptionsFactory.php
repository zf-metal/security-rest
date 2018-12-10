<?php

namespace ZfMetal\SecurityRest\Factory\Options;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class ModuleOptionsFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
         $config = $container->get('Config');
         
         return new \ZfMetal\SecurityRest\Options\ModuleOptions(isset($config['zf-metal-security-rest.options']) ? $config['zf-metal-security-rest.options'] : array());
    }

}
