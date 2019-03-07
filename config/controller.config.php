<?php

return [
    'controllers' => [
        'factories' => [
            \ZfMetal\SecurityRest\Controller\UserController::class => \ZfMetal\SecurityRest\Factory\Controller\UserControllerFactory::class,
            \ZfMetal\SecurityRest\Controller\RoleController::class => \ZfMetal\SecurityRest\Factory\Controller\RoleControllerFactory::class,
            \ZfMetal\SecurityRest\Controller\RegisterController::class => \ZfMetal\SecurityRest\Factory\Controller\RegisterControllerFactory::class,
            \ZfMetal\SecurityRest\Controller\RecoveryController::class => \ZfMetal\SecurityRest\Factory\Controller\RecoveryControllerFactory::class,
            \ZfMetal\SecurityRest\Controller\PasswordChangeController::class => \ZfMetal\SecurityRest\Factory\Controller\PasswordChangeControllerFactory::class,
            \ZfMetal\SecurityRest\Controller\ImageChangeController::class => \ZfMetal\SecurityRest\Factory\Controller\ImageChangeControllerFactory::class,
        ]
    ]
];
