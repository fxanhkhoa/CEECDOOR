<?php

namespace Form\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Captcha;

class FormElement extends Form{

    public function __construct(){
      parent::__construct();
      // Set POST method for this form
      $this->setAttribute('method', 'post');

      // (Optionally) set action for this form
      $this->setAttribute('action', '/about');

      // Create a text element to capture the MSSV:
      $MSSV = new Element('MSSV');
      $MSSV->setLabel('MSSV');
      $MSSV->getLabelAttributes([
        'id' => 'MSSV',
        'class' => 'control-label'
      ]);
      $MSSV->setAttributes([
        'type' => 'text',
        'class' => 'form-control input-text',
        'id' => 'MSSV',
        'placeholder' => 'Student ID',
        'data-rule' => 'minlen:4',
        'data-msg' => 'Please enter at least 4 chars'
      ]);

      // Create a text element to capture the NAME:
      $NAME = new Element('NAME');
      $NAME->setLabel('NAME');
      $NAME->getLabelAttributes([
        'id' => 'NAME',
        'class' => 'control-label'
      ]);
      $NAME->setAttributes([
        'type' => 'text',
        'class' => 'form-control input-text',
        'id' => 'NAME',
        'placeholder' => 'Student Name',
        'data-rule' => 'minlen:4',
        'data-msg' => 'Please enter at least 4 chars'
      ]);

      //Create a Email element
      $EMAIL = new Element('EMAIL');
      $EMAIL->setLabel('EMAIL');
      $EMAIL->getLabelAttributes([
        'id' => 'EMAIL',
        'class' => 'control-label'
      ]);
      $EMAIL->setAttributes([
        'type' => 'email',
        'class' => 'form-control input-text',
        'id' => 'EMAIL',
        'placeholder' => 'Email',
        'data-rule' => 'email',
        'data-msg' => 'Please enter valid email',
        'required' => true
      ]);

      //Create Select
      $SELECT = new Element\Select('mySelect');
      $SELECT->setLabel('Pick RFID')
             ->setLabelAttributes([
                'id' => 'select',
                'class'=>'control-label'
             ])
             ->setAttributes([
                'class' => 'form-control',
                'id' => 'select',
             ])
             ->setValueOptions([
                'abcd'=>'ABCD',
                'aaaa'=>'AAAA',
             ]);

      //Create file
      $FILE = new Element\File('myFile');
      $FILE->setLabel('Choose a picture')
           ->setLabelAttributes([
                'id' => 'image',
                'class' => 'control-label',
           ])
           ->setAttributes([
                'id' => 'image',
                'class' => 'form-control'
           ]);

      //Reset button
      $RESETBTN = new Element('myResetButton');
      $RESETBTN->setValue('reset')
               ->setAttributes([
                 'type' => 'reset',
                 'id' => 'Reset',
                 'class' => 'input-btn',
                 'value' => 'Reset',
           ]);

      //Submit button
      $SUBMITBTN = new Element('submitButton');
      $SUBMITBTN->setValue('Submit')
                ->setAttributes([
                  'type' => 'submit',
                  'class' => 'input-btn'
                ]);

      //Captcha image
      $Captcha = new Element\Captcha('captcha');
      $Captcha->setCaptcha(new Captcha\Image())
              ->setLabel('Make sure you are human')
              ->getCaptcha()->setImgDir('public/img/captcha')
                            ->setFont('public/fonts/fontawesome-webfont.ttf')
                            ->setImgUrl('img/captcha')
                            ->setSuffix('.png')
                            ->setExpiration('60')
                            ->setGcFreq('10');

      $this->add($MSSV);
      $this->add($NAME);
      $this->add($EMAIL);
      $this->add($SELECT);
      $this->add($FILE);
      $this->add($RESETBTN);
      $this->add($SUBMITBTN);
      $this->add($Captcha);
    }
}
 ?>
