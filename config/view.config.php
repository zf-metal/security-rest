<?php


return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view'
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'view_helpers' => [
            'factories' => [
                'getSecurityRestOptions' => \ZfMetal\SecurityRest\Factory\Helper\View\OptionsFactory::class,
            ],
        ],
    ],
];