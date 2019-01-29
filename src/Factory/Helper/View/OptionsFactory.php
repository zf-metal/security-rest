<?php

namespace ZfMetal\SecurityRest\Factory\Helper\View;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * OptionsFactory
 *
 *
 *
 * @author
 * @license
 * @link
 */
class OptionsFactory implements FactoryInterface
{

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $moduleOptions = $container->get('zf-metal-security-rest.options');
        return new \ZfMetal\SecurityRest\Helper\View\Options($moduleOptions);
    }


}

