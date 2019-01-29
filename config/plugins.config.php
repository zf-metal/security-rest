<?php


return [
    'controller_plugins' => [
        'factories' => [
            \ZfMetal\SecurityRest\Controller\Plugin\ModuleOptions::class => \ZfMetal\SecurityRest\Factory\Controller\Plugin\ModuleOptionsFactory::class,

        ],
        'aliases' => [
            'getSecurityRestOptions' => \ZfMetal\SecurityRest\Controller\Plugin\ModuleOptions::class
        ]
    ]
];
