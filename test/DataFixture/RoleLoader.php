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
use ZfMetal\Security\Entity\Role;

class RoleLoader extends AbstractFixture implements FixtureInterface
{

    const ENTITY = Role::class;

    const INVITADO = 'invitado';
    const USUARIO = 'usuario';
    const ADMIN = 'admin';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ArrayCollection
     */
    protected $roles;

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
    public function getRoles()
    {
        if (!$this->roles) {
            $this->roles = new ArrayCollection();
        }
        return $this->roles;
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

        $this->createRole(1, self::INVITADO);
        $this->createRole(2, self::USUARIO);
        $this->createRole(3, self::ADMIN);
        $manager->flush();


    }


    public function createRole($id, $name)
    {

        $role = $this->findByName($name);
        if (!$role) {
            $role = new Role();
            //$role->setId($id); //No setId method exist. Fix It!
            $role->setName($name);
        }
        $this->getEm()->persist($role);

        $this->getRoles()->add($role);
    }

}