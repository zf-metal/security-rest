<?php

namespace ZfMetal\SecurityRest\Controller;


use ZfMetal\Restful\Controller\MainController;
use ZfMetal\Security\Entity\User;

class UserController extends MainController
{

    /**
     * @var string
     */
    protected $entityClass = User::class;


    /**
     * @var string
     */
    protected $entityAlias = "users";

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

    public function getUserRepository()
    {
        $this->getEm()->getRepository(User::class);
    }

    public function getEntityRepository()
    {
        return parent::getEntityRepository(User::class);
    }



}
