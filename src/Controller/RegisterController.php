<?php

namespace ZfMetal\SecurityRest\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use ZfMetal\Mail\MailManager;
use ZfMetal\Security\Entity\User;
use ZfMetal\Security\Form\Register;

/**
 * Class RegisterController
 * @package ZfMetal\SecurityRest\Controller
 * @method \ZfMetal\Security\Options\ModuleOptions getSecurityOptions
 * @method \ZfMetal\SecurityRest\Options\ModuleOptions getSecurityRestOptions
 * @method MailManager mailManager
 */
class RegisterController extends AbstractActionController
{


    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;


    /**
     *
     * @var Register
     */
    protected $form;

    function __construct(\Doctrine\ORM\EntityManager $em, Register $form)
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

    function setEm(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function registerAction()
    {

        $response = [];
        $message = '';
        $errors = [];
        $status = false;


        if (!$this->getSecurityOptions()->getPublicRegister()) {
            $this->redirect()->toRoute('home');
        }

        $user = new \ZfMetal\Security\Entity\User();

        $this->form->setHydrator(new \DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity($this->getEm()));
        $this->form->bind($user);


        if ($this->getRequest()->isPost()) {
            $this->form->setData($this->getRequest()->getPost());

            if ($this->form->isValid()) {
                $user->setPassword($this->bcrypt()->encode($user->getPassword()));

                $message = '';
                if ($this->getSecurityOptions()->getEmailConfirmationRequire()) {
                    $user->setActive(0);
                    $role = $this->getEm()->getRepository(\ZfMetal\Security\Entity\Role::class)->findOneBy(['name' => $this->getSecurityOptions()->getRoleDefault()]);
                    if (!$role) {
                        throw new \Exception('The role ' . $this->getSecurityOptions()->getRoleDefault() . ' no exist!');
                    }
                    $user->addRole($role);
                    $this->getUserRepository()->saveUser($user);
                    $message = 'El usuario fue creado correctamente. Requiere activación via email.';

                    if ($this->notifyUser($user)) {
                        $message = 'Envio de mail exitoso. Verifique su casilla de Email para activar el usuario.';
                        $status = true;
                    } else {
                        $message = 'Envio de mail fallido. Contacte al administrador.';
                    }
                } else {
                    $role = $this->getEm()->getRepository(\ZfMetal\Security\Entity\Role::class)->findOneBy(['name' => $this->getSecurityOptions()->getRoleDefault()]);
                    if (!$role) {
                        throw new \Exception('The role ' . $this->getSecurityOptions()->getRoleDefault() . ' no exist!');
                    }
                    $user->addRole($role);
                    $user->setActive($this->getSecurityOptions()->getUserStateDefault());
                    $this->getUserRepository()->saveUser($user);
                    $status = true;
                    $message = 'El usuario fue creado correctamente.';

                    if (!$this->getSecurityOptions()->getUserStateDefault()) {
                        $message .= 'El usuario debe ser habilitado por un administrador.';
                    }
                }

            } else {
                foreach ($this->form->getMessages() as $key => $messages) {
                    foreach ($messages as $msj) {
                        $errors[$key][] = $msj;
                    }
                }
            }
        }

        $response["status"] = $status;
        $response["message"] = $message;

        if ($errors) {
            $response["errors"] = $errors;
        }

        return new JsonModel($response);
    }

    public function notifyUser(\ZfMetal\Security\Entity\User $user)
    {
        $token = $this->stringGenerator()->generate();

        $link = $this->getSecurityRestOptions()->getWebHost(). $this->url()->fromRoute('zf-metal-security-rest/validate', ['id' => $user->getId(), 'token' => $token], ['force_canonical' => false]);

        $tokenObj = new \ZfMetal\Security\Entity\Token();

        $tokenObj->setUser($user)
            ->settoken($token);

        $tokenRepository = $this->em->getRepository(\ZfMetal\Security\Entity\Token::class);

        $tokenRepository->saveToken($tokenObj);

        $this->mailManager()->setTemplate('zf-metal/security-rest/mail/validate', ["user" => $user, "link" => $link]);
        $this->mailManager()->setFrom($this->getMailFrom());
        $this->mailManager()->addTo($user->getEmail(), $user->getName());
        $this->mailManager()->setSubject('Activación de cuenta de ' . $this->getSecurityRestOptions()->getWebHost());

        if ($this->mailManager()->send()) {
            return true;
        } else {
            $this->logger()->err("Falla al enviar mail de confirmación de cuenta.");
            return false;
        }
    }


    protected function getMailFrom()
    {
        return $this->getSecurityOptions()->getMailFrom();
    }

    public function validateAction()
    {

        $status = false;

        $id = $this->params('id');
        $token = $this->params("token");

        $tokenRepository = $this->em->getRepository(\ZfMetal\Security\Entity\Token::class);

        $tokenObj = $tokenRepository->getTokenByUserIdAndToken($id, $token);

        if (!$tokenObj) {
            $status = false;
            $message = "La cuenta no se pudo confirmar. El token no es valido o ha expirado";
        }

        try {
            $user = $this->getUserRepository()->find($id);

            if ($user) {
                $user->setActive(true);
                $this->getUserRepository()->saveUser($user);
                $status = true;
                $message = "La cuenta ha sido confirmada con Exito";
                $tokenRepository->removeToken($tokenObj);

            }
        } catch (\Exception $e) {
            $status = false;
            $message = "Hubo un problema al intentar activar tu cuenta.";
        }


        $response = [
            "status" => $status,
            "message" => $message
        ];

        return new JsonModel($response);
    }


}
