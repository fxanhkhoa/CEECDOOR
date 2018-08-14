<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Entity\Users;
use Users\Form\UserForm;

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
}

?>