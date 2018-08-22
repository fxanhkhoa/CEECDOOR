<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Regex;
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;
use Zend\Authentication\AuthenticationService;

return [
    'router' => [
        'routes' => [
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/user[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'setpassword' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/set-password[/:token]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'setPassword',
                    ],
                ],
                'constraints'=>[
                    'action' => '[a-zA-Z0-9_-]*',
                ]
            ],
            'resetpassword' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/reset-password[/:token]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'resetPassword',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            __NAMESPACE__.'_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ],
            ],

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => [
                'drivers' => [
                    // register __NAMESPACE__.'_driver' for any entity under namespace `Users\Entity`
                    __NAMESPACE__.'\Entity' => __NAMESPACE__.'_driver',
                ],
            ],
        ],
    ],
    'service_manager'=>[
        'factories' => [
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            AuthenticationService::class=>Service\Factory\AuthenticationService::class,
        ],
    ]
];
