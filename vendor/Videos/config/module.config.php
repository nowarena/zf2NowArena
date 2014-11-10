<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Videos\Controller\Index' => 'Videos\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'videos' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/videos/[:controller[/:action]]',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Videos\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Videos' => __DIR__ . '/../view',
        ),
    ),
    'console' => array(
    	'router' => array(
    		'routes' => array(
    			'youtubecronroute' => array(
    				'options' => array(
    					'route'    => 'youtubecron [<env>]',
    					'defaults' => array(
    						'controller' => 'Videos\Controller\Index',
    						'action' => 'youtubecron'
    					)
    				)
    			)
    		),
    	),
    ),        
);
