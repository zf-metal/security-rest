<?php

namespace ZfMetal\SecurityRest\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class RegisterControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $em = $container->get(\Doctrine\ORM\EntityManager::class);


        return new \ZfMetal\SecurityRest\Controller\RegisterController($em);
    }

}
