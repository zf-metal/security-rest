<?php

return [
    'controllers' => [
        'factories' => [
            \ZfMetal\SecurityRest\Controller\UserController::class => \ZfMetal\SecurityRest\Factory\Controller\UserControllerFactory::class,
          \ZfMetal\SecurityRest\Controller\RegisterController::class => \ZfMetal\SecurityRest\Factory\Controller\RegisterControllerFactory::class,
            \ZfMetal\SecurityRest\Controller\RecoveryController::class => \ZfMetal\SecurityRest\Factory\Controller\RecoveryControllerFactory::class
           ]
    ]
];
