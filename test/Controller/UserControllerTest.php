<?php

namespace Test\Controller;

use Doctrine\ORM\EntityManager;

use PHPUnit\Framework\TestCase;
use Zend\Crypt\Password\Bcrypt;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\ServiceManager\ServiceManager;
use ZendTest\Http\HeadersTest;
use ZfMetal\Security\Repository\UserRepository;
use ZfMetal\SecurityJwt\Controller\JwtController;
use ZfMetal\SecurityJwt\Options\ModuleOptions;
use ZfMetal\SecurityJwt\Service\JwtService;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class UserControllerTest
 * @method Request getRequest()
 * @package Test\Controller
 */
class UserControllerTest extends AbstractHttpControllerTestCase
{


    protected $mockedEm;
    protected $mockedUserRepository;
    protected $mockedUser;


    /**
     * Inicializo el MVC
     */
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../config/application.config.php'
        );
        parent::setUp();
        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    /**
     * Mockeo el EntityManager sobre el contenedor de servicios
     * @param ServiceManager $services
     */
    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);
        $services->setService(EntityManager::class, $this->getMockEntityManager());
        $services->setAllowOverride(false);
    }

    public function getMockEntityManager()
    {
        //Mock EntityManager
        $this->mockedEm = $this->createMock(EntityManager::class);

        //Mock UserRepositorey
        $this->mockedUserRepository = $this->createMock(UserRepository::class);

        //Mock method getRepository
        $this->mockedEm->method('getRepository')
            ->willReturn($this->mockedUserRepository);

        //Mock method getAuthenticateByEmailOrUsername on UserRepository
        $map = [
            ['userInvalid', null],
            ['userValid', $this->getMockUser()]
        ];
        $this->mockedUserRepository->method('getAuthenticateByEmailOrUsername')
            ->will($this->returnValueMap($map));

        //Mock method find on UserRepository
        $mapFind = [
            [null, null],
            [1, $this->getMockUser()]
        ];
        $this->mockedUserRepository->method('find')
            ->willReturn($this->getMockUser());
        //  ->will($this->returnValueMap($mapFind));

        return $this->mockedEm;
    }

    public function getMockUser()
    {
        if (!$this->mockedUser) {
            $this->mockedUser = new \ZfMetal\Security\Entity\User();
            $this->mockedUser->setId(1);
            $this->mockedUser->setUsername("JhonDoe");
            $this->mockedUser->setActive(true);
            $bcrypt = new Bcrypt(['cost' => 12]);
            $password = $bcrypt->create("validPassword");
            $this->mockedUser->setPassword($password);
        }
        return $this->mockedUser;
    }


    /**
     * Verico que con metodo GET obtengo 404 not found
     */
    public function testGet()
    {
        $this->dispatch("/security/api/users/1", "GET");


        $response = json_decode($this->getResponse()->getContent());

        $this->assertResponseStatusCode(200);

        $this->assertEquals($response->id, $this->getMockUser()->getId());
        $this->assertEquals($response->username, $this->getMockUser()->getUsername());
        $this->assertEquals($response->active, $this->getMockUser()->getActive());

    }


    /**
     * Verico que con metodo GET obtengo 404 not found
     */
    public function testGetList()
    {
        $this->dispatch("/security/api/users", "GET");

        $json = [
            'id' => 1,
            'username' => 'JhonDoe',
            'active' => true
        ];


        $this->assertResponseStatusCode(200);
        $this->assertJsonStringEqualsJsonString($this->getResponse()->getContent(), json_encode($json));
    }

}
