<?php

namespace ZfMetal\SecurityRest\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfMetal\Security\Form\ImageForm;

class ImageChangeControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {

        $em = $container->get('doctrine.entitymanager.orm_default');
        $form = $container->get('FormElementManager')->get(ImageForm::class);

        return new \ZfMetal\SecurityRest\Controller\ImageChangeController($em,$form);
    }

}
