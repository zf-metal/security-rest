<?php

return [
    'router' => [
        'routes' => [
            'zf-metal-security-rest' => [
                'type' => \Zend\Router\Http\Literal::class,
                'may_terminate' => false,
                'options' => [
                    'route' => '/security/api',
                ],
                'child_routes' => [
                    'users' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/users[/:id]',
                            'defaults' => [
                                'controller' => \ZfMetal\SecurityRest\Controller\UserController::class,
                            ]
                        ],
                    ],
                    'register' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/register',
                            'defaults' => [
                                'controller' => \ZfMetal\SecurityRest\Controller\RegisterController::class,
                                'action' => 'register'
                            ]
                        ],
                    ],
                    'recovery' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/recovery',
                            'defaults' => [
                                'controller' => \ZfMetal\SecurityRest\Controller\RecoveryController::class,
                                'action' => 'recovery'
                            ]
                        ],
                    ],
                ]
            ],

        ]
    ]
];
