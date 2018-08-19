<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Barcode\Barcode;
use Zend\Router\Http\Regex;
use Form\Form\Login;
use Users\Controller\UserController;
use Application\Database\Database;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
      $form = new Login();

      if ($this->getRequest()->isPost()){
          $data = $this->params()->fromPost();
          $form->setData($data);

          if ($form->isValid()){
              $data = $form->getData();
              print_r($data);
          }
      }

      //Get data from USERUSAGE
      date_default_timezone_set("Asia/Ho_Chi_Minh");
      $database = new Database();
      $users = $database->getHistory();

      $view = new ViewModel(['users'=>$users]);
      $this->layout()->setVariable('form', $form); // Set variable to get from layout content by using $this->layout()->form in layout.phtml
      $view->setTemplate('application/index/index');
    //   print_r($view);
      return $view;
    }

    public function aboutAction()
    {
        $appName = 'CEEC';
        $appDescription = 'A sample application for the Using Zend Framework 3 book';

        $form = new Login();

        if ($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()){
                $data = $form->getData();
                print_r($data);
            }
        }
        // Return variables to view script with the help of
        // ViewModel variable container
        $this->layout()->setVariable('form', $form);
        $view = new ViewModel([
          'appName' => $appName,
          'appDescription' => $appDescription,
        //   'form' => $form
        ]);
        // print_r($view);
        return $view;
    }

    public function checkAction()
    {
    }

    public function historypostAction()
    {
      return new ViewModel();
    }

    public function getJsonAction()
    {
        return new JsonModel([
            'status' => 'SUCCESS',
            'message'=>'Here is your data',
            'data' => [
                'full_name' => 'John Doe',
                'address' => '51 Middle st.'
            ]
        ]);
    }

    // The "barcode" action
    public function barcodeAction()
    {
        // Get parameters from route.
        $type = $this->params()->fromRoute('type', 'code39');
        $label = $this->params()->fromRoute('label', 'HELLO-WORLD');

        // Set barcode options.
        $barcodeOptions = ['text' => $label];
        $rendererOptions = [];

        // Create barcode object
        $barcode = Barcode::factory(
        $type,
        'image',
                 $barcodeOptions,
        $rendererOptions
    );

        // The line below will output barcode image to standard
        // output stream.
        $barcode->render();

        // Return Response object to disable default view rendering.
        return $this->getResponse();
    }
    public function docAction()
    {
        $pageTemplate = 'application/index/doc'.
        $this->params()->fromRoute('page', 'documentation.phtml');

        $filePath = __DIR__.'/../../view/'.$pageTemplate.'.phtml';
        if (!file_exists($filePath) || !is_readable($filePath)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $viewModel = new ViewModel([
            'page'=>$pageTemplate
        ]);
        $viewModel->setTemplate($pageTemplate);

        return $viewModel;
    }
    public function partialDemoAction()
    {
        $products = [
    [
      'id' => 1,
      'name' => 'Digital Camera',
      'price' => 99.95,
    ],
    [
      'id' => 2,
      'name' => 'Tripod',
      'price' => 29.95,
    ],
    [
      'id' => 3,
      'name' => 'Camera Case',
      'price' => 2.99,
    ],
    [
      'id' => 4,
      'name' => 'Batteries',
      'price' => 39.99,
    ],
    [
      'id' => 5,
      'name' => 'Charger',
      'price' => 29.99,
    ],
  ];

        return new ViewModel(['products' => $products]);
    }

    public function loginAction()
    {
        $checkMethod = $this->getRequest();
        if ($checkMethod ->isGet())
        {
          echo "using GET";
        }
        return false;
    }
}
