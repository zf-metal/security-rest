<?php

namespace ZfMetal\SecurityRest\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfMetal\Security\Form\Register;


class RegisterControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $em = $container->get(\Doctrine\ORM\EntityManager::class);
        $form = $container->get('FormElementManager')->get(Register::class);

        return new \ZfMetal\SecurityRest\Controller\RegisterController($em,$form);
    }

}
