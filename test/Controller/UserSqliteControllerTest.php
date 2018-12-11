<?php

namespace Test\Controller;


use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Test\DataFixture\PermissionLoader;
use Test\DataFixture\RoleLoader;
use Test\DataFixture\UserLoader;
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

    public function testCreateData(){
        $loader = new Loader();
        $loader->addFixture(new PermissionLoader());
        $loader->addFixture(new RoleLoader());
        $loader->addFixture(new UserLoader());

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->getEm(), $purger);
        $executor->execute($loader->getFixtures());
    }


    /**
     * Verico que con metodo GET y parametro ID 1 obtengo el usuario requerido (administrator)
     */
    public function testGetAdministrator()
    {
        $this->setUseConsoleRequest(false);
        $this->dispatch("/security/api/users/1", "GET");


        $response = json_decode($this->getResponse()->getContent());

        $this->assertResponseStatusCode(200);

        $this->assertEquals($response->id, 1);
        $this->assertEquals($response->username, "administrator");
        $this->assertEquals($response->active, true);

    }

    /**
     * Verico que con metodo GET y parametro ID 2 obtengo el usuario requerido (jhondoe)
     */
    public function testGetJhonDoe()
    {
        $this->setUseConsoleRequest(false);
        $this->dispatch("/security/api/users/2", "GET");


        $response = json_decode($this->getResponse()->getContent());

        $this->assertResponseStatusCode(200);

        $this->assertEquals($response->id, 2);
        $this->assertEquals($response->username, "JhonDoe");
        $this->assertEquals($response->active, true);

    }


    public function testGetList(){
        $this->setUseConsoleRequest(false);
        $this->dispatch("/security/api/users", "GET");

        $response = json_decode($this->getResponse()->getContent());


        $this->assertResponseStatusCode(200);

        $this->assertEquals($response[0]->id, 1);
        $this->assertEquals($response[0]->username, "administrator");
        $this->assertEquals($response[0]->active, true);

        $this->assertEquals($response[1]->id, 2);
        $this->assertEquals($response[1]->username, "JhonDoe");
        $this->assertEquals($response[1]->active, true);

    }
}
