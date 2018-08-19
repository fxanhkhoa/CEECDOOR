<?php
namespace Users\Form;

use Zend\Form\Form;
use Zend\Captcha;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;

class ResetPasswordForm extends Form{
    public function __construct(){
        parent::__construct();

        $this->setAttributes([
            'name'=>'reset-pw',
            'class'=>'form-horizontal'
        ]);

        $this->addElements();
        $this->addValidator();
    }

    private function addElements(){
    
        //confirm new password
        $this->add([
            'type'=>'email',
            'name'=>'email',
            'options'=>[
                'label'=>'Email',
                'label_attributes'=>[
                    'for'=>'confirm_new_pw',
                    'class'=>'control-label'
                ]
            ],
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input Email',
            ]
        ]);

        //csrf
        $this->add([
            'type'=>'csrf',
            'name'=>'csrf',
            'options'=>[
                'csrf_options'=>[
                    'timeout'=>300 // 5minute
                ]
            ]
        ]);

        //Captcha image
        $Captcha = new Element\Captcha('CAPTCHA');
        $Captcha->setCaptcha(new Captcha\Image())
            ->setLabel('Make sure you are human')
            ->getCaptcha()->setImgDir('public/img/captcha')
            ->setFont('public/fonts/arial.ttf')
            ->setImgUrl('../img/captcha')
            ->setSuffix('.png')
            ->setExpiration(300)
            ->setGcFreq(10)
            ->setFontSize(24)
            ->setWidth(350)
            ->setHeight(100)
            ->setDotNoiseLevel(40)
            ->setLineNoiseLevel(3)
            ->setWordlen(4);

        $this->add($Captcha);

        //button
        //btn
        $this->add([
            'type'=>'submit',
            'name'=>'btnSubmit',
            'attributes'=>[
                'class'=>'btn btn-success',
                'value'=>'Save',
            ],
        ]);
    }

    private function addValidator(){

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name'=>'email',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
                ['name'=>'StripNewlines'],
            ],
            'validators'=>[
                [
                    'name'=>'NotEmpty',
                ],
                [
                    'name'=>'StringLength',
                    'options'=>[
                        'min'=>10,
                        'max'=>50
                    ]
                ],
                [
                    'name'=>'EmailAddress'
                ]
            ]
        ]);
    }
}
?>