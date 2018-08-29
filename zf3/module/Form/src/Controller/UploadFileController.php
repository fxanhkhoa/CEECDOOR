<?php

namespace Form\Controller;

use Form\Form\UploadFile;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http;
use Zend\Filter\File\Rename;
use Zend\Validator\File\Upload;

class UploadFileController extends AbstractActionController{

    public function indexAction(){
        $form = new UploadFile();
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $file = $request->getFiles()->toArray();
            // echo '<pre>';
            // print_r($file);
            // echo '</pre>';

            // $fileUpload = new Http();
            // $fileInfo = $fileUpload->getFileInfo();
            // echo '<pre>';
            // print_r($fileInfo);
            // echo '</pre>';

            // echo $fileUpload->getFileSize();
            // echo $fileUpload->getFileName();
            // echo FILES_PATH.'upload';

            /* Upload with temp name */
            // $fileUpload->setDestination(FILES_PATH.'upload/');
            // $fileUpload->receive();

            $form->setData($file);
            if ($form->isValid()){
                $fileFilter = new Rename([
                    'target' => FILES_PATH.'upload/'.$file['file-upload']['name'],
                    'randomize' => false,
                ]);
    
                $fileFilter->filter($file['file-upload']);
    
                echo "<script>
                    alert('File Uploaded!');
                    
                    </script>";
            }

            else{
                $messages = $form->getMessages();
                // foreach ($messages as $error){
                //     echo $error.'<br>';
                // }
            }
            
        }

        $view = new ViewModel(['form'=>$form]);
        $view->setTemplate('form/upload-file/index');
        return $view;
    }

    public function uploadMultipleAction(){
        $form = new UploadFile();
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $file = $request->getFiles()->toArray();

            $form->setData($file);

            if ($form->isValid()){

                $data = $form->getData();
                
                foreach ($data['file-upload'] as $eachfile){
                    $fileFilter = new Rename([
                        'target' => FILES_PATH.'upload/'.$eachfile['name'],
                        'randomize' => false,
                    ]);
        
                    $fileFilter->filter($eachfile);
                    echo $eachfile['name'].' done'.'<br>';
                }
                echo "<script>
                    alert('File Uploaded!');
                    
                    </script>";
            }

            else{
                $messages = $form->getMessages();
                // foreach ($messages as $error){
                //     echo $error.'<br>';
                // }
            }
        }

        $view = new ViewModel(['form'=>$form]);
        $view->setTemplate('form/upload-file/multiple');
        return $view;
    }
}