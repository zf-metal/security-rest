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
use ZfMetal\SecurityRest\Controller\RoleController;


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

    public function getEm()
    {
        return $this->getApplicationServiceLocator()->get(EntityManager::class);
    }

    public function testUseOfRouter()
    {
        $this->assertEquals(true, $this->useConsoleRequest);
    }


    /**
     * Se genera la estructura de la base de datos (Creacion de tablas)
     */
    public function testGenerateStructure()
    {

        $this->dispatch('orm:schema-tool:update --force');
        $this->assertResponseStatusCode(0);
        //$this->assertConsoleOutputContains("Updating database schema");
    }

    /**
     * Se popula las tablas con datos necesarios (Permisos, Roles, Usuarios y sus relaciones)
     */
    public function testCreateData()
    {
        $loader = new Loader();
        $loader->addFixture(new PermissionLoader());
        $loader->addFixture(new RoleLoader());
        $loader->addFixture(new UserLoader());

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->getEm(), $purger);
        $executor->execute($loader->getFixtures());
        $this->assertResponseStatusCode(0);
    }


    /**
     * METHOD GET
     * ACTION get
     * DESC Obtener un registro especifico (administrator)
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
     * METHOD GET
     * ACTION get
     * DESC Obtener un registro especifico (JhonDoe)
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

        // $this->assertJsonStringEqualsJsonString($this->getResponse()->getContent(), "{}");

    }

    /**
     * METHOD GET
     * ACTION getlist
     * DESC Obtener un listado de registros
     */
    public function testGetList()
    {
        $this->setUseConsoleRequest(false);
        $this->dispatch("/security/api/users", "GET");

        $response = json_decode($this->getResponse()->getContent());
        //var_dump($response);

        $this->assertResponseStatusCode(200);

        $this->assertEquals($response[0]->id, 1);
        $this->assertEquals($response[0]->username, "administrator");
        $this->assertEquals($response[0]->active, true);

        $this->assertEquals($response[1]->id, 2);
        $this->assertEquals($response[1]->username, "JhonDoe");
        $this->assertEquals($response[1]->active, true);

    }


    /**
     * @depends testCreateData
     * METHOD POST
     * ACTION create
     * DESC crear un nuevo usuario
     */

    public function testCreate()
    {

        $this->setUseConsoleRequest(false);

        //Create Firt User

        $params = [
            "username" => "userCreate",
            "email" => "userCreate@zfmetal.com",
            "name" => "userCreate",
            "active" => true,
            "password" => "123"
        ];

        $this->dispatch("/security/api/users", "POST",
            $params);

        $jsonToCompare = [
            "status" => true,
            'id' => 4,
            "message" => "The item was created successfully"
        ];

        var_dump($this->getResponse()->getContent());
        $this->assertJsonStringEqualsJsonString( json_encode($jsonToCompare),$this->getResponse()->getContent());
        $this->assertResponseStatusCode(201);


        $this->reset();

        //Create Second User (to be update)
        $params = [
            "username" => "userToUpdate",
            "email" => "userToUpdate@zfmetal.com",
            "name" => "userToUpdate",
            "active" => true,
            "password" => "789"
        ];

        $this->dispatch("/security/api/users", "POST",
            $params);

        $jsonToCompare = [
            "status" => true,
            'id' => 5,
            "message" => "The item was created successfully"
        ];

        $this->assertJsonStringEqualsJsonString($this->getResponse()->getContent(), json_encode($jsonToCompare));
        $this->assertResponseStatusCode(201);

        $this->reset();
        //Create Third User (to be delete)
        $params = [
            "username" => "userToDelete",
            "email" => "userToDelete@zfmetal.com",
            "name" => "userToDelete",
            "active" => true,
            "password" => "789"
        ];

        $this->dispatch("/security/api/users", "POST",
            $params);

        $jsonToCompare = [
            "status" => true,
            'id' => 6,
            "message" => "The item was created successfully"
        ];

        $this->assertJsonStringEqualsJsonString($this->getResponse()->getContent(), json_encode($jsonToCompare));
        $this->assertResponseStatusCode(201);

    }


    /**
     * @depends testCreate
     * METHOD PUT
     * ACTION update
     * DESC actualizo un usuario
     */

    public function testUpdate()
    {

        $this->setUseConsoleRequest(false);

        $params = [
            "username" => "userUpdated",
            "email" => "userUpdated@zfmetal.com",
            "name" => "userUpdated",
            "active" => true,
            "password" => "456"
        ];

        $this->dispatch("/security/api/users/5", "PUT",
            $params);


        $jsonToCompare = [
            "status" => true,
            'id' => 5,
            "message" => "The item was updated successfully"
        ];

        $this->assertJsonStringEqualsJsonString($this->getResponse()->getContent(), json_encode($jsonToCompare));
        $this->assertResponseStatusCode(200);
    }

    /**
     * @depends testUpdate
     * METHOD DELETE
     * ACTION delete
     * DESC elimino un usuario
     */

    public function testDelete()
    {

        $this->setUseConsoleRequest(false);


        $this->dispatch("/security/api/users/6", "DELETE");


        $jsonToCompare = [
            "status" => true,
            "message" => "Item Delete"
        ];

        $this->assertJsonStringEqualsJsonString($this->getResponse()->getContent(), json_encode($jsonToCompare));
        $this->assertResponseStatusCode(200);
    }


    /**
     * METHOD GET
     * ACTION get
     * DESC Obtener un registro especifico (administrator)
     */
    public function testGetInvalidId()
    {
        $this->setUseConsoleRequest(false);
        $this->dispatch("/security/api/users/23", "GET");


        $response = json_decode($this->getResponse()->getContent());

        $this->assertResponseStatusCode(404);

        $jsonToCompare = [
            "status" => false,
            "message" => "The item does not exist"
        ];

        $this->assertJsonStringEqualsJsonString($this->getResponse()->getContent(), json_encode($jsonToCompare));

    }


    /**
     * METHOD GET
     * ACTION getlRoleist
     * DESC Obtener un listado de registros
     */
    public function testGetRoleList()
    {
        $this->setUseConsoleRequest(false);
        $this->dispatch("/security/api/roles", "GET");

        $jsonToCompare = [
            [
                "id" => 1,
                "name" => RoleLoader::INVITADO,
                'children' => null,
                'permissions' => [
                    ['id' => 1, 'name' => 'show']
                ]
            ],
            [
                "id" => 2,
                "name" => RoleLoader::USUARIO,
                'children' => null,
                'permissions' => [
                    ['id' => 1, 'name' => 'show'],
                    ['id' => 3, 'name' => 'edit']

                ]
            ],
            [
                "id" => 3,
                "name" => RoleLoader::ADMIN,
                'children' => null,
                'permissions' => [
                    ['id' => 1, 'name' => 'show'],
                    ['id' => 2, 'name' => 'create'],
                    ['id' => 3, 'name' => 'edit'],
                    ['id' => 4, 'name' => 'delete']
                ]
            ]
        ];

        $this->assertControllerName(RoleController::class);
        $this->assertJsonStringEqualsJsonString(json_encode($jsonToCompare), $this->getResponse()->getContent());

    }

}
