<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Database;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'router' => [
        'routes' => [
            'database' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/adapter[/:action]',
                    'defaults' => [
                        'controller' => Controller\AdapterController::class,
                        'action'     => 'index',
                    ],
                    'constrains' => [
                        'action' => '[a-zA-Z0-9_-]* '
                    ]
                ],
            ],
            'sql' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/sql[/:action]',
                    'defaults' => [
                        'controller' => Controller\SqlController::class,
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
            Controller\AdapterController::class=>InvokableFactory::class,
            Controller\SqlController::class=>InvokableFactory::class,
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
