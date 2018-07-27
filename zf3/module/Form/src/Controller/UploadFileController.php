<?php

namespace Form\Controller;

use Form\Form\UploadFile;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http;

class UploadFileController extends AbstractActionController{

    public function indexAction(){
        $form = new UploadFile();
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $file = $request->getFiles();
            // echo '<pre>';
            // print_r($file);
            // echo '</pre>';

            $fileUpload = new Http();
            $fileInfo = $fileUpload->getFileInfo();
            echo '<pre>';
            print_r($fileInfo);
            echo '</pre>';

            echo $fileUpload->getFileSize();
        }

        return new ViewModel(['form'=>$form]);
    }
}