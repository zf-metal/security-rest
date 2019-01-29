<?php

namespace ZfMetal\SecurityRest\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ModuleOptions extends AbstractPlugin
{
    /**
     * @var \ZfMetal\SecurityRest\Options\ModuleOptions
     */
    private $moduleOptions;

    /**
     * ModuleOptions constructor.
     * @param $moduleOptions
     */
    public function __construct(\ZfMetal\SecurityRest\Options\ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @return \ZfMetal\SecurityRest\Options\ModuleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    function __invoke()
    {
        return $this->getModuleOptions();
    }
}