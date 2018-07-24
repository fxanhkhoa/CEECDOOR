<?php

namespace Form\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter;
use Form\Form\FormElement;

class Login extends Form
{
    public function __construct()
    {
        parent::__construct();
        //Dinh nghia form
        $this->loginForm();
        //Dinh nghia filter + validate
        $this->loginInputFilter();
    }

    //Create TextField
    private function loginForm()
    {
        //EMAIL
        $email = new Element\Email('email');
        $email->setLabel('Email')
              ->setLabelAttributes([
                  'for' => 'email',
                  'class' => 'control-label',
              ])
              ->setAttributes([
                  'id' => 'email',
                  'class' => 'form-control',
                  'placeholder' => 'example@domain.com',
              ]);


        //Password
        $pw = new Element\Password('password');
        $pw->setLabel('Password')
                 ->setLabelAttributes([
                    'for' => 'password',
                    'class' => 'control-label'
                 ])
                 ->setAttributes([
                    'id' => 'password',
                    'class' => 'form-control',
                    'placeholder' => 'Enter your pass',
                 ]);

        $rememberMe = new Element\Checkbox('remember');
        $rememberMe->setLabel('Remember me: ')
                   ->setLabelAttributes([
                     'for' => 'remember'
                   ])
                   ->setAttributes([
                     'id' => 'remember',
                     'value'=>1,
                     'required'=>false,
                   ]);

        //Button submit
        $submit = new Element\Submit('submit');
        $submit->setAttributes([
            'value' => 'Login',
            'class' => 'btn btn-success'
        ]);

        $this->add($email);
        $this->add($pw);
        $this->add($rememberMe);
        $this->add($submit);
    }


    //Create InputFilter
    private function loginInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();
        $this->setInputFilter($inputFilter);
        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filter' => [
              //Trim - newline
              ['name' => 'StringTrim'],
            ],
            'validators' => [
              [
              'name' => 'EmailAddress',
              'options' => [
                  'messages' => [
                      \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email khong dung dinh dang',
                      \Zend\Validator\EmailAddress::INVALID_HOSTNAME => 'Hostname ko dung'
                  ]
              ]
            ]
            ]
        ]);

        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'filter' => [
              //Trim - newline
              ['name' => 'StringTrim'],
              ['name' => 'StripTags'],
              ['name' => 'StripNewlines'],
            ],
            'validators' => [
              [
                'name' => 'StringLength',
                'options' => [
                    'min' => 6,
                    'max' => 30,
                    'messages' => [
                        \Zend\Validator\StringLength::TOO_SHORT => 'Mat khau it nhat %min% ki tu',
                        \Zend\Validator\StringLength::TOO_LONG => 'Mat khau ko qua %max% ki tu'
                    ]
                ]
              ]
            ]
        ]);
    }
}
