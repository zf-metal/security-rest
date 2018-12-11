<?php
/**
 * Created by PhpStorm.
 * User: crist
 * Date: 11/12/2018
 * Time: 00:14
 */

namespace Test\DataFixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\ORM\EntityManager;
use ZfMetal\Security\Entity\Permission;

class PermissionLoader  extends AbstractFixture implements FixtureInterface
{

    const ENTITY = Permission::class;

    const SHOW = 'show';
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ArrayCollection
     */
    protected $permissions;

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
    public function getPermissions()
    {
        if (!$this->permissions) {
            $this->permissions = new ArrayCollection();
        }
        return $this->permissions;
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

        $this->createPermission(1, self::SHOW);
        $this->createPermission(2, self::CREATE);
        $this->createPermission(3, self::EDIT);
        $this->createPermission(4, self::DELETE);
        $manager->flush();


    }


    /**
     * @param $id
     * @param $name
     */
    public function createPermission($id, $name)
    {

        $permission = $this->findByName($name);
        if (!$permission) {
            $permission = new Permission($name);
            $permission->setId($id);
        }


        $this->getEm()->persist($permission);

        //Add reference for relations
        $this->addReference($name, $permission);

        $this->getPermissions()->add($permission);
    }

}