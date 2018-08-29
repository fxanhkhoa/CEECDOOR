<?php

namespace Form\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\Input;
use Zend\Validator\File\Size;
use Zend\Validator\File\FilesSize;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\ExcludeMimeType;
use Zend\Validator\File\Upload;

class UploadFile extends Form{

    public function __construct(){
        parent::__construct();

        $this->add([
            'name'=>'file-upload',
            'attributes'=>[
                'type'=>'file',
                'multiple'=>true,
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

        //Validator
        $this->uploadInputFilter();
    }

    public function uploadInputFilter(){
        $fileUpload = new FileInput('file-upload');
        $fileUpload->setRequired(true);
        //fileSize
        $size = new Size([
            'max'=>'10MB', 
        ]);
        $size->setMessage([
            Size::TOO_BIG => 'File too big, size < %max%',
        ]);

        //MimeType
        //image/png, image/jpeg, image/jpg
        $mimeType = new MimeType([
            'image',
            'audio',
            'application',
            'text',
        ]);
        $mimeType->setMessages([
            MimeType::FALSE_TYPE => 'File extension not accepted',
            MimeType::NOT_DETECTED => 'Not detected file extension',
            MimeType::NOT_READABLE => 'Cannot read',
        ]);

        $fileUpload->getValidatorChain()
                ->attach($size, true, 2)
                ->attach($mimeType, true, 1);

        // $fileUpload->add($size);
        //         ->add($mimeType);

        $inputFilter = new InputFilter();
        $inputFilter->add($fileUpload);
        $this->setInputFilter($inputFilter);
    }
}