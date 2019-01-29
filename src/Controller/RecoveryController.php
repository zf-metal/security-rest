<?php

namespace ZfMetal\SecurityRest\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mail;

class RecoveryController extends AbstractActionController {



    /**
     * @var \ZfMetal\Security\Form\Recover
     */
    private $form;

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;



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

    function setEm(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * RecoverController constructor.
     * @param $userRepository
     */
    public function __construct(\Doctrine\ORM\EntityManager $em, \ZfMetal\Security\Form\Recover $form) {
        $this->userRepository = $userRepository;
        $this->form = $form;
    }
    


    public function recoveryAction() {
        /* @var $form \Zend\Form\Form */
        $form = $this->form;

        $status = false;
        $errors = '';

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);


            if ($form->isValid()) {
                $user = $this->getUserRepository()->findOneByEmail($data['email']);
                $status = $this->updatePasswordUserAndNotify($user);

            } else {
                foreach ($form->getMessages() as $key => $messages) {
                    foreach ($messages as $msj) {
                        $errors[$key][] = $msj;
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


    public function updatePasswordUserAndNotify(\ZfMetal\Security\Entity\User $user) {
        $newPassword = $this->stringGenerator()->generate();

        if (!$newPassword) {
            $this->logger()->err("Falla al generar nueva clave");
            throw new \Exception('Falla al generar nueva clave');
        }

        $user->setPassword($this->bcrypt()->encode($newPassword));

        try {
            $this->getUserRepository()->saveUser($user);
        } catch (Exception $ex) {
            $this->logger()->err("Falla al intentar guardar en la DB cambio de password");
        }


        $result = $this->notifyUser($user, $newPassword);
        return $result;
    }

    public function notifyUser(\ZfMetal\Security\Entity\User $user, $newPassword) {

        $this->mailManager()->setTemplate('zf-metal/security/mail/reset', ["user" => $user, "newPassowrd" => $newPassword]);

        $this->mailManager()->setFrom($this->getSecurityOptions()->getMailFrom());
        $this->mailManager()->addTo($user->getEmail(), $user->getName());
        $this->mailManager()->setSubject('Recuperar Password');

        if ($this->mailManager()->send()) {
             return true;
        } else {
            $this->logger()->info("Falla al enviar mail al resetear password.");
            return false;
        }
    }

}
