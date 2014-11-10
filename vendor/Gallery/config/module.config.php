<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Gallery\Controller\Index' => 'Gallery\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'getmonthlynav' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/getmonthlynav',
                    'constraints' => array(
					),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Gallery\Controller',
                        'controller'    => 'Index',
                        'action'        => 'getMonthlyNav',
                    ),
                ),
            ),
            'getthumbs' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/gallery/getthumbs[/:date][/:page]',
                    'constraints' => array(
                        'date' => '[0-9]+-[a-zA-z]+',
                        'page' => '[0-9]*',
					),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Gallery\Controller',
                        'controller'    => 'Index',
                        'action'        => 'getthumbs',
                    ),
                ),
            ),
            'gallery' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/gallery[/:date][/:page]',
                    'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'date' => '[0-9]+-[a-zA-z]+',
                        'page' => '[0-9]*',
					),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Gallery\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
            ),
            'disallow' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/gallery/disallow[/:id]',
                    'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'id' => '[0-9]*',
					),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Gallery\Controller',
                        'controller'    => 'Index',
                        'action'        => 'disallow',
                    ),
                ),
            ),
            'picture' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/gallery/picture[/:idposition][/:id]',
                    'constraints' => array(
                        'idposition' => '[a-z]*',
                        'id' => '[0-9]*',
					),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Gallery\Controller',
                        'controller'    => 'Index',
                        'action'        => 'picture',
                    ),
                ),
            ),
            'forsale' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/forsale',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Gallery\Controller',
                        'controller'    => 'Index',
                        'action'        => 'forsale',
                    ),
                ),
            ),
            'code' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/code',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Gallery\Controller',
                        'controller'    => 'Index',
                        'action'        => 'code',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Gallery' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
   
);
