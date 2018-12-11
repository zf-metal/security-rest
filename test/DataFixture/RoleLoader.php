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

        $this->createRole(1, self::INVITADO, [PermissionLoader::SHOW]);
        $this->createRole(2, self::USUARIO, [PermissionLoader::SHOW, PermissionLoader::EDIT]);
        $this->createRole(3, self::ADMIN, [PermissionLoader::SHOW, PermissionLoader::CREATE, PermissionLoader::EDIT, PermissionLoader::DELETE]);
        $manager->flush();


    }


    public function createRole($id, $name, array $permissions = [])
    {

        $role = $this->findByName($name);
        if (!$role) {
            $role = new Role();
            //$role->setId($id); //No setId method exist. Fix It!
            $role->setName($name);

            foreach($permissions as $permission){
                $role->addPermission($this->getReference($permission));
            }
        }


        $this->getEm()->persist($role);

        //Add reference for relations
        $this->addReference($name, $role);

        $this->getRoles()->add($role);
    }

}