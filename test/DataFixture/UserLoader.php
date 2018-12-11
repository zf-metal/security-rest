<?php

namespace Test\DataFixture;

/**
 * Created by PhpStorm.
 * User: crist
 * Date: 1/6/2018
 * Time: 12:21
 */
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\ORM\EntityManager;
use ZfMetal\Security\Entity\Permission;
use ZfMetal\Security\Entity\Role;
use ZfMetal\Security\Entity\User;

class UserLoader extends AbstractFixture implements FixtureInterface
{

    const ENTITY = User::class;

    const ADMINISTRATOR = 'administrator';
    const JHONDOE = 'JhonDoe';
    const JANEDOE = 'JaneDoe';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ArrayCollection
     */
    protected $users;

    /**
     * @return mixed
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        if (!$this->users) {
            $this->users = new ArrayCollection();
        }
        return $this->users;
    }


    protected function findByName($name)
    {
        return $this->getEm()->getRepository($this::ENTITY)->findOneByName($name);
    }


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $this->em = $manager;

        $this->createUser(1, self::ADMINISTRATOR,true,"123",'admin@zfmetal.com', RoleLoader::ADMIN);
        $this->createUser(2, self::JHONDOE,true,"123",'jhondoe@zfmetal.com', RoleLoader::USUARIO);
        $this->createUser(3, self::JANEDOE,true,"123",'janedoe@zfmetal.com', RoleLoader::INVITADO);
        $manager->flush();


    }


    public function createUser($id, $name,$active,$password,$email,$role)
    {

        $user = $this->findByName($name);
        if (!$user) {
            $user = new User();
            $user->setId($id);
            $user->setUsername($name);
            $user->setActive($active);
            $user->setName($name);
            $user->setPassword($password);
            $user->setEmail($email);
            $user->addRole($this->getReference($role));
        }


        $this->getEm()->persist($user);

        //Add reference for relations
        $this->addReference($name, $user);

        $this->getUsers()->add($user);
    }

}