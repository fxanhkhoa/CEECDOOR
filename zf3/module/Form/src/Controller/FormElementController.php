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
}
