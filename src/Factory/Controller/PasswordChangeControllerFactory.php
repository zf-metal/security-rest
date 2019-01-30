<?php

namespace ZfMetal\SecurityRest\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfMetal\Security\Form\PasswordChangeForm;

class PasswordChangeControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $em = $container->get('doctrine.entitymanager.orm_default');
        $form = $container->get('FormElementManager')->get(PasswordChangeForm::class);
        return new \ZfMetal\SecurityRest\Controller\PasswordChangeController($em,$form);
    }

}
