<?php

namespace ZfMetal\SecurityRest\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use ZfMetal\Security\Entity\User;
use ZfMetal\Security\Form\PasswordChangeForm;

class PasswordChangeController extends AbstractActionController
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     *
     * @var PasswordChangeForm
     */
    private $form;

    /**
     * @var User
     */
    private $user;

    /**
     * PasswordChangeController constructor.
     * @param \Doctrine\ORM\EntityManager $em
     * @param PasswordChangeForm $form
     */
    public function __construct(\Doctrine\ORM\EntityManager $em, PasswordChangeForm $form)
    {
        $this->em = $em;
        $this->form = $form;
    }


    /**
     * @return \ZfMetal\Security\Repository\UserRepository
     */
    public function getUserRepository()
    {
        return $this->getEm()->getRepository(User::class);
    }


    function getEm()
    {
        return $this->em;
    }

    /**
     * @return PasswordChangeForm
     */
    public function getForm()
    {
        return $this->form;
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


    public function passwordChangeAction()
    {

        $status = false;
        $errors = '';
        $message = '';

        if (!$this->getIdentityUser()) {
            $message = 'Usuario no identificado';
        } else {

            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->form->setData($data);

                if ($this->form->isValid()) {


                    $this->getIdentityUser()->setPassword($this->bcrypt()->encode($data['password']));
                    $this->getUserRepository()->saveUser($this->getIdentityUser());
                    $status = true;
                    $message = 'Contraseña actualizada con exito.';
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
            'message' => $message
        ]);
    }

}
