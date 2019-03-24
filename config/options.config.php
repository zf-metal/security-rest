<?php
return [
    'zf-metal-security-rest.options' => [
        'web_host' => (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : "",
    ],
    'zf-metal-restful.options' => [
        'return_item_on_update' => true,
    ],
];
