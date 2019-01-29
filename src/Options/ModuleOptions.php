<?php

namespace ZfMetal\SecurityRest\Options;

use Zend\Stdlib\AbstractOptions;
use ZfcRbac\Options\RedirectStrategyOptions;

/**
 */
class ModuleOptions extends AbstractOptions
{

    /**
     * Enable Public Register
     *
     * @var boolean
     */
    protected $webHost = '';


    /**
     * @return bool
     */
    public function getWebHost()
    {
        return $this->webHost;
    }

    
    /**
     * @return bool
     */
    public function isWebHost()
    {
        return $this->webHost;
    }

    /**
     * @param bool $webHost
     */
    public function setWebHost($webHost)
    {
        $this->webHost = $webHost;
    }


    
}
