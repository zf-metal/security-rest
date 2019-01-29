<?php

namespace ZfMetal\SecurityRest\Helper\View;

/**
 * Options
 *
 * @author
 * @license
 * @link
 */
class Options extends \Zend\View\Helper\AbstractHelper
{

    private $moduleOptions = null;

    public function __invoke()
    {
        return $this->moduleOptions;
    }

    public function __construct(\ZfMetal\SecurityRest\Options\ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
    }


}

