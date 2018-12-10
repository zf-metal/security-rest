<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => \Doctrine\DBAL\Driver\PDOSqlite\Driver::class,
                'params' => array(
                    'path' => __DIR__."/../data/security.db"
                )
            ),
        )
    )
);