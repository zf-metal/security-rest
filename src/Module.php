<?php

namespace ZfMetal\SecurityRest;

use Zend\Crypt\Password\Bcrypt;
use Zend\EventManager\Event;

use ZfMetal\Restful\Controller\MainController;
use ZfMetal\Security\Entity\User;
use ZfMetal\SecurityRest\Controller\UserController;
use ZfMetal\SecurityRest\Listener\PasswordListener;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    public function onBootstrap(\Zend\Mvc\MvcEvent $mvcEvent)
    {

        /** @var \Zend\EventManager\SharedEventManager $sharedEventManager */
        $sharedEventManager = $mvcEvent->getApplication()->getEventManager()->getSharedManager();

        $sharedEventManager->attach(
            UserController::class, // Event manager 'identifier', which one we want
            'create_users_before',                    // Name of event to listen to
            [$this, 'encryptPassword'],   // The event listener to trigger
            1                      // event priority
        );

    /*    $sharedEventManager->attach(
            UserController::class,
            'update_users_before',
            [$this, 'encryptPassword'],
            1
        );*/

    }

    public function encryptPassword(Event $event){
        $object = $event->getParam('object');
        if($object instanceof User){
            $bcrypt = new Bcrypt();
            $bcrypt->setCost(12);
            $object->setPassword($bcrypt->create($object->getPassword()));
        }else{
            throw new Exception("Arg Object must be a User Entity");
        }

    }

}
