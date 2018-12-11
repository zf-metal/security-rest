<?php

namespace Test\Controller;


use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Test\DataFixture\RoleLoader;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;



/**
 * Class UserControllerTest
 * @method Request getRequest()
 * @package Test\Controller
 */
class UserSqliteControllerTest extends AbstractConsoleControllerTestCase
{

    protected $traceError = true;

    /**
     * Inicializo el MVC
     */
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../config/application.config.php'
        );
        parent::setUp();
    }

    public function getEm(){
        return $this->getApplicationServiceLocator()->get(EntityManager::class);
    }

    public function testUseOfRouter()
    {
        $this->assertEquals(true, $this->useConsoleRequest);
    }


    public function testGenerateStructure()
    {
        $this->dispatch('orm:schema-tool:update --force');
        $this->assertResponseStatusCode(0);
        $this->assertConsoleOutputContains("Updating database schema");
    }

    public function testCreateRoleData(){
        $loader = new Loader();
        $loader->addFixture(new RoleLoader());

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->getEm(), $purger);
        $executor->execute($loader->getFixtures());
    }

}
