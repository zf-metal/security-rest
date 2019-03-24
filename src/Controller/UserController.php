<?php

namespace ZfMetal\SecurityRest\Controller;


use Zend\View\Model\JsonModel;
use ZfMetal\Restful\Controller\MainController;
use ZfMetal\Restful\Transformation\Policy\Auto;
use ZfMetal\Restful\Transformation\Policy\Skip;
use ZfMetal\Restful\Transformation\Transform;
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
    public function __construct(\Doctrine\ORM\EntityManager $em, \ZfMetal\Security\Form\User $form)
    {
        $this->em = $em;

        $this->policies = [
            'password' => new Skip(),
          //  'roles' => new Skip()
        ];

        $this->setForm($form);
    }

    public function getUserRepository()
    {
        $this->getEm()->getRepository(User::class);
    }

    public function getEntityRepository()
    {
        return parent::getEntityRepository(User::class);
    }

    public function update($id, $data)
    {

        $id = $this->params("id");
        $data["id"] = $id;

        $this->getForm()->remove("password");
        return parent::update($id, $data);
    }


}
