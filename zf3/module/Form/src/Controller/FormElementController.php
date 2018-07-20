<?php

namespace Form\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Form\Form\FormElement;

class FormElementController extends AbstractActionController{

  public function indexAction(){
      $form = new FormElement();
      $view = new ViewModel(['form' => $form]);
      return $view;
  }

  public function getFormDataAction(){
      $form = new FormElement();
      $request = $this->getRequest();
      if ($request->isPost()){
          $data = $this->params()->fromPost();
          $file = $request->getFiles();
          echo "<pre>";
          print_r($data);
          echo "</pre>";

          echo "<pre>";
          print_r($file);
          echo "</pre>";
      }
      $view = new ViewModel(['form' => $form]);
      $view->setTemplate('form/form-element/get-data');
      return $view;
  }
}
