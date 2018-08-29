<?php

namespace Users\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;

class UsersUsageForm extends Form{
    
    private $action;
    public function __construct($action = "add"){
        parent::__construct();
        $this->setAttributes([
            'name'=>'user-form',
            'class'=>'form-horizontal',
            'action'=>'#',
        ]);
        $this->action = $action;
        $this->addElements();
        $this->validator();
    }

    private function addElements()
    {
        $this->add([
            'type'=>'text',
            'name'=>'rfid',
            'attributes'=>[
                'class'=>'form-control',
                'placeholder'=>'Input RFID',
                'id'=>'rfid',
            ],
            'options'=>[
                'label'=>'RFID:',
                'label_attributes'=>[
                    'for'=>'rfid',
                    'class'=>' control-label',
                ]
            ],
        ]);
    }

    private function validator()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        //rfid
        $inputFilter->add([
            'name'=>'rfid',
            'required'=>true,
            'filters'=>[
                ['name'=>'StringTrim'],
                ['name'=>'StripTags'],
                ['name'=>'StripNewlines'],
            ],
            'validators'=>[
                [
                    'name'=>'NotEmpty',
                    'options'=>[
                        'break_chain_on_failure'=>true,
                        'messages'=>[
                            NotEmpty::IS_EMPTY=>'RFID can not be empty'
                        ]
                    ]
                ],
                [
                    'name'=>'StringLength',
                    'options'=>[
                        'min'=>8,
                        'max'=>10,
                        'messages'=>[
                            StringLength::TOO_SHORT=>'Min is %min%',
                            StringLength::TOO_LONG=>'Max is %max%',
                        ]
                    ]
                ]
            ]
        ]);

        //btn
        $this->add([
            'type'=>'submit',
            'name'=>'btnSubmit',
            'attributes'=>[
                'class'=>'btn btn-success',
                'value'=>'Post',
            ],
        ]);
    }
}
?>