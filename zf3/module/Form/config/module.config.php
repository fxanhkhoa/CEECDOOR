<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Form;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Regex;
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'router' => [
        'routes' => [
            'form' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/form[/:action]',
                    'defaults' => [
                        'controller' => Controller\FormElementController::class,
                        'action'     => 'index',
                    ],
                    'constrains' => [
                        'action' => '[a-zA-Z0-9_-]* '
                    ]
                ],
            ],
            'validator' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/validator[/:action]',
                    'defaults' => [
                        'controller' => Controller\ValidatorController::class,
                        'action'     => 'string',
                    ],
                    'constrains' => [
                        'action' => '[a-zA-Z0-9_-]* '
                    ]
                ],
            ],
            'input-filter' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login[/:action]',
                    'defaults' => [
                        'controller' => Controller\InputFilterController::class,
                        'action'     => 'index',
                    ],
                    'constrains' => [
                        'action' => '[a-zA-Z0-9_-]* '
                    ]
                ],
            ],
            'file' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/upload-file[/:action]',
                    'defaults' => [
                        'controller' => Controller\UploadFileController::class,
                        'action'     => 'index',
                    ],
                    'constrains' => [
                        'action' => '[a-zA-Z0-9_-]* '
                    ]
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\FormElementController::class => InvokableFactory::class,
            Controller\ValidatorController::class => InvokableFactory::class,
            Controller\InputFilterController::class => InvokableFactory::class,
            Controller\UploadFileController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => '127.0.0.1',
                    'user'     => 'fxanhkhoa',
                    'password' => '03021996',
                    'dbname'   => 'id6432065_door',
                ]
            ],
        ],
    ],
];
