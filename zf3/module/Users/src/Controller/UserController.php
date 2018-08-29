<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Entity\Users;
use Users\Form\UserForm;
use Users\Form\UsersUsageForm;
use Users\Form\ChangePasswordForm;
use Users\Form\ResetPasswordForm;

class UserController extends AbstractActionController{

    private $entityManager;
    private $userManager;

    public function __construct($entityManager, $userManager){
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function indexAction(){
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $users = $this->entityManager->getRepository(Users::class)->findAll();
        //$users = $this->entityManager->getRepository(Users::class)->findBy([]);
        $view = new ViewModel(['users'=>$users]);
        // print_r($users);
        // $view->setTemplate('Users/user/index');
        // echo __DIR__ . '/../view' ;

        return $view;
    }

    public function addAction(){
        $form = new UserForm('add');
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        // echo date("Y-m-d");
        if ($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()){
                $data = $form->getData();
                // echo '<pre>';
                // print_r($data);
                // echo '</pre>';
                $user = $this->userManager->addUser($data);
                // echo '<pre>';
                // print_r($user);
                // echo '</pre>';
                $this->flashMessenger()->addSuccessMessage('Add Successfully');
                return $this->redirect()->toRoute('user',[
                    'controller'=>'user',
                    'action'=>'add',
                ]);
            }
        }

        $view = new ViewModel(['form'=>$form]);
        return $view;
    }

    public function editAction(){
        $idUser = $this->params()->fromRoute('id',0);
        if ($idUser <= 0){
            $this->getReponse()->setStatusCode('404');
            return;
        }

        //Get user info
        $user = $this->entityManager->getRepository(Users::class)->find($idUser);
        if (!$user){
            $this->getReponse()->setStatusCode('404');
            return;
        }

        //
        $form = new UserForm('edit');
        if (!$this->getRequest()->isPost()){
            $data = [
                'username'=> $user->getUSERNAME(),
                'rfid'=>$user->getRFID(),
                'email'=>$user->getEMAIL(),
                'fullname'=>$user->getFULLNAME(),
                'birthday'=>$user->getBIRTHDAY(),
                'gender'=>$user->getGENDER(),
                'address'=>$user->getADDRESS(),
                'phone'=>$user->getPHONE(),
                'role'=>$user->getROLE(),
            ];
            $form->setData($data);
            return new ViewModel(['form'=>$form, 'user'=>$user]);
        }
        
        //Post
        $data = $this->params()->fromPost();
        $form->setData($data);

        if ($form->isValid()){
            $data = $form->getData();
            $this->userManager->editUser($user, $data);
            $this->flashMessenger()->addSuccessMessage('Edit Successfully');
            return $this->redirect()->toRoute('user',[
                'controller'=>'user',
                'action'=>'index',
            ]);
        }
    }

    public function deleteAction(){
        $idUser = $this->params()->fromRoute('id',0);
        if ($idUser <= 0){
            $this->getReponse()->setStatusCode('404');
            return;
        }

        //Get user info
        $user = $this->entityManager->getRepository(Users::class)->find($idUser);
        if (!$user){
            $this->getReponse()->setStatusCode('404');
            return;
        }

        if ($this->getRequest()->isPost()){
            $btn = $this->getRequest()->getPost('delete', 'No');
            if ($btn == 'Yes'){
                $this->userManager->removeUser($user);
                $this->flashMessenger()->addSuccessMessage('Delete Successfully');
            }
            return $this->redirect()->toRoute('user');
        }

        return new ViewModel(['user'=>$user]);
    }

    public function changePasswordAction(){
        $idUser = $this->params()->fromRoute('id',0);
        if ($idUser <= 0){
            $this->getReponse()->setStatusCode('404');
            return;
        }

        //Get user info
        $user = $this->entityManager->getRepository(Users::class)->find($idUser);
        if (!$user){
            $this->getReponse()->setStatusCode('404');
            return;
        }

        $form = new ChangePasswordForm();
        if($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()){
                $data = $form->getData();
                $check = $this->userManager->changePassword($user, $data);

                if (!$check){
                    $this->flashMessenger()->addErrorMessage('Old Pass is wrong, please check again');
                    return $this->redirect()->toRoute('user',['action'=>'change-password','id'=>$user->getId()]);
                }
                else{
                    $this->flashMessenger()->addSuccessMessage('Password Changed');
                    return $this->redirect()->toRoute('user');
                }
            }
        }
        return new ViewModel(['form'=>$form]);
    }

    public function resetPasswordAction(){
        $form = new ResetPasswordForm();
        // echo $_SERVER['HTTP_HOST'];

        if ($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()){
                // print_r($data);
                $user = $this->entityManager->getRepository(Users::class)->findOneBy(['EMAIL' => $data['email']]);
                if ($user !== null){
                    $this->userManager->createTokenPasswordReset($user);
                    $this->flashMessenger()->addSuccessMessage('Check Email for reset password');
                }
                else{
                    $this->flashMessenger()->addErrorMessage('Email does not exist');
                }
                return $this->redirect()->toRoute('user',['action'=>'reset-password']);
            }
        }

        return new ViewModel(['form'=>$form]);
    }

    public function setPasswordAction(){
        $token = $this->params()->fromRoute('token', null);
        if ($token == null || strlen($token) !=32){
            throw new \Exception("Invalid Token");
        }
        else if (!$this->userManager->checkResetPasswordToken($token)){
            throw new \Exception("Invalid Token or Timeout. Please try again");
        }

        $form = new ChangePasswordForm('resetPw');

        if ($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()){
                if($this->userManager->setNewPasswordByToken($token, $data['new_pw'])){
                    $this->flashMessenger()->addSuccessMessage('Reset successful');
                }
                else{
                    $this->flashMessenger()->addErrorMessage('Reset fail');
                }
                return $this->redirect()->toRoute('reset-password');
            }
        }
        
        $view = new ViewModel(['form'=>$form]);
        return $view;
    }

    public function reportAction(){
        $form = new UsersUsageForm('add');
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        // echo date("Y-m-d");
        if ($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()){
                $data = $form->getData();
                // echo '<pre>';
                // print_r($data);
                // echo '</pre>';
                $user = $this->userManager->addUSERUSAGE($data);
                // echo '<pre>';
                // print_r($user);
                // echo '</pre>';
                $this->flashMessenger()->addSuccessMessage('Add Successfully');
                return $this->redirect()->toRoute('user',[
                    'controller'=>'user',
                    'action'=>'report',
                ]);
            }
        }

        $view = new ViewModel(['form'=>$form]);
        return $view;
    }
}

?>