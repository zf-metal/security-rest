<?php

namespace ZfMetal\SecurityRest\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class RecoveryControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $em = $container->get(\Doctrine\ORM\EntityManager::class);

        //INIT FORM AND FILTER
        $form = new \ZfMetal\Security\Form\Recover();
        $emailExist = new \ZfMetal\Security\Validator\EmailExist(["userRepository" => $userRepository]);
        $filter = new \ZfMetal\Security\Form\Filter\RecoverFilter($emailExist);
        $form->setInputFilter($filter);

        return new \ZfMetal\SecurityRest\Controller\RecoveryController($em,$form);
    }

}
