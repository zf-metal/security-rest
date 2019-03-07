<?php

namespace ZfMetal\SecurityRest\Controller;


use Zend\View\Model\JsonModel;
use ZfMetal\Restful\Controller\MainController;
use ZfMetal\Restful\Transformation\Policy\Auto;
use ZfMetal\Restful\Transformation\Policy\Skip;
use ZfMetal\Restful\Transformation\Transform;
use ZfMetal\Security\Entity\Role;
use ZfMetal\Security\Entity\User;

class RoleController extends MainController
{

    /**
     * @var string
     */
    protected $entityClass = Role::class;


    /**
     * @var string
     */
    protected $entityAlias = "roles";

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;


    /**
     * UserController constructor.
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getRoleRepository()
    {
        $this->getEm()->getRepository(Role::class);
    }

    public function getEntityRepository()
    {
        return parent::getEntityRepository(Role::class);
    }


}
