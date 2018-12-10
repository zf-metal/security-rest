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
                ]
            ],

        ]
    ]
];
