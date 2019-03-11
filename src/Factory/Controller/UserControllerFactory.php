<?php

namespace ZfMetal\SecurityRest\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class UserControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $em = $container->get(\Doctrine\ORM\EntityManager::class);
        $form = $container->get('FormElementManager')->get(\ZfMetal\Security\Form\User::class);

        return new \ZfMetal\SecurityRest\Controller\UserController($em,$form);
    }

}
