<?php

namespace Form\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class UploadFile extends Form{

    public function __construct(){
        parent::__construct();

        $this->add([
            'name'=>'file-upload',
            'attributes'=>[
                'type'=>'file',
            ],
            'option'=>[
                'label'=>'Choose File'
            ]
        ]);

        //Button submit
        $submit = new Element\Submit('submit');
        $submit->setAttributes([
            'value' => 'Upload',
            'class' => 'btn btn-success'
        ]);

        $this->add($submit);
    }
}