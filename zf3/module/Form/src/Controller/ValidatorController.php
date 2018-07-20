<?php

namespace Form\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Validator\ValidatorInterface;
use Zend\Validator\StringLength;

class ValidatorController extends AbstractActionController{
    public function stringAction(){
        $validator = new StringLength(['min' => 6]);
        $var = "qwertyu";
        if ($validator->isValid($var)){
            echo $var;
        }
        else{
            $messages = $validator->getMessages();
            foreach($messages as $error){
                echo $error.'<br>';
            }
        }
        return false;
    }

    //number
    public function numberAction(){
        $validator = new \Zend\Validator\Between([
            'min' => 5,
            'max' => 10,
            'inclusive' => false,
        ]);
        $var = 4;
        if ($validator->isValid($var)){
            echo $var;
        }
        else{
            $messages = $validator->getMessages();
            foreach($messages as $error){
                echo $error.'<br>';
            }
        }
        return false;
    }
}
