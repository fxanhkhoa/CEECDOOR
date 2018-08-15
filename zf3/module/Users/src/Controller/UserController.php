<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Entity\Users;
use Users\Form\UserForm;
use Users\Form\UsersUsageForm;

class UserController extends AbstractActionController{

    private $entityManager;
    private $userManager;

    public function __construct($entityManager, $userManager){
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function indexAction(){
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
        echo date("Y-m-d");
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

    public function reportAction(){
        $form = new UsersUsageForm('add');
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        echo date("Y-m-d");
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