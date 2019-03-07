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
                    'roles' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/roles[/:id]',
                            'defaults' => [
                                'controller' => \ZfMetal\SecurityRest\Controller\RoleController::class,
                            ]
                        ],
                    ],
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
                    'validate' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/validate/:id/:token',
                            'defaults' => [
                                'controller' => \ZfMetal\SecurityRest\Controller\RegisterController::class,
                                'action' => 'validate'
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
                    'passwordChange' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/password-change',
                            'defaults' => [
                                'controller' => \ZfMetal\SecurityRest\Controller\PasswordChangeController::class,
                                'action' => 'password-change'
                            ]
                        ],
                    ],
                    'imageChange' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/image-change',
                            'defaults' => [
                                'controller' => \ZfMetal\SecurityRest\Controller\ImageChangeController::class,
                                'action' => 'image-change'
                            ]
                        ],
                    ],
                ]
            ],

        ]
    ]
];
