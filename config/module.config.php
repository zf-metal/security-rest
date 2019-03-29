<?php

namespace ZfMetal\Security;

return array_merge_recursive(
    include 'routes.config.php',
    include 'options.config.php',
    include 'controller.config.php',
    include 'services.config.php',
    include 'plugins.config.php',
    include 'view.config.php'
);