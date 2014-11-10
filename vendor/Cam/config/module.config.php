<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Cam\Controller\Cam' => 'Cam\Controller\CamController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'cam' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/cam[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Cam\Controller',
                        'controller'    => 'Cam',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
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
            'Cam' => __DIR__ . '/../view',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'camgirlcronroute' => array( 
                    'options' => array(
                    	'route'    => 'camgirlcron <env>',
                    	'defaults' => array(
                    	   'controller' => 'Cam\Controller\Cam',
                    	   'action' => 'readlj'
                    	)
                    )
                ),
                'topcamgirlcronroute' => array( 
                    'options' => array(
                    	'route'    => 'topcamgirlcron <env>',
                    	'defaults' => array(
                    	   'controller' => 'Cam\Controller\Banner',
                    	   'action' => 'topcamgirl'
                    	)
                    )
                )
            ),
        ),
    ),
);
