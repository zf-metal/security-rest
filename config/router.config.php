<?php

use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'zf-metal-security-rest' => [
                'type' => Literal::class,
                'may_terminate' => false,
                'options' => [
                    'route' => '/security/api',
                ],
                'child_routes' => [
                    'user' => [
                        'type' => \Zend\Router\Http\Segment::class,
                        'may_terminate' => false,
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
