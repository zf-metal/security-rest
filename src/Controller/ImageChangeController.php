<?php

namespace ZfMetal\SecurityRest\Controller;

use Zend\Config\Processor\Constant;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfMetal\Security\Constants;
use ZfMetal\Security\Entity\User;
use ZfMetal\Security\Form\ImageForm;

class ImageChangeController extends AbstractActionController
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     *
     * @var ImageForm
     */
    private $form;


    /**
     * @var User
     */
    private $user;

    function getEm()
    {
        return $this->em;
    }

    /**
     * @return \ZfMetal\Security\Repository\UserRepository
     */
    public function getUserRepository()
    {
        return $this->getEm()->getRepository(User::class);
    }

    public function getIdentityUser()
    {
        if (!$this->user) {
            $user = $this->getJwtIdentity();
            if ($user && is_a($user, User::class)) {
                $this->user = $this->getUserRepository()->find($user->getId());
            }
        }

        return $this->user;
    }


    /**
     * ImageChangeController constructor.
     * @param \Doctrine\ORM\EntityManager $em
     * @param ImageForm $form
     */
    public function __construct(\Doctrine\ORM\EntityManager $em, ImageForm $form)
    {
        $this->em = $em;
        $this->form = $form;
    }


    public function imageChangeAction()
    {

        $status = false;
        $errors = '';
        $message = '';
        $img = null;

        if (!$this->getIdentityUser()) {
            $message = 'Usuario no identificado';
        } else {

            if ($this->request->isPost()) {


                $data = array_merge_recursive(
                    $this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray()
                );

                $this->form->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($this->getEm()));

                $this->form->bind($this->getIdentityUser());
                $this->form->setData($data);

                if ($this->form->isValid()) {

                    $this->getUserRepository()->saveUser($this->getIdentityUser());

                    //@TODO To Review this mess. WTF
                    $this->Identity()->setImg($this->getIdentityUser()->getImg());

                    $status = true;

                    $img = Constants::IMG_RELATIVE_PATH. $this->getIdentityUser()->getImg();

                    $message = 'La imagen se actualizó correctamente.';

                } else {
                    foreach ($form->getMessages() as $key => $messages) {
                        foreach ($messages as $msj) {
                            $errors[$key][] = $msj;
                        }
                    }
                }
            }
        }
        return new JsonModel([
            'status' => $status,
            'errors' => $errors,
            'message' => $message,
            'img' => $img
        ]);
    }


}
