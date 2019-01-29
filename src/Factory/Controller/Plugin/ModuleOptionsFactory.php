<?php

namespace ZfMetal\SecurityRest\Factory\Controller\Plugin;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ModuleOptionsFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $moduleOptions = $container->get('zf-metal-security-rest.options');
        return new \ZfMetal\SecurityRest\Controller\Plugin\ModuleOptions($moduleOptions);
    }

}
