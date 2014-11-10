<?php
return array(

    'controllers' => array(
        'invokables' => array(
            'Instagram\Controller\Instagram' => 'Instagram\Controller\InstagramController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'instagram' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/instagram',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Instagram\Controller',
                        'controller'    => 'Instagram',
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
            'Instagram' => __DIR__ . '/../view',
        ),
    ),
    'console' => array(
    	'router' => array(
    		'routes' => array(
                'instagramcronroute' => array(
                    'options' => array(
                        'route'    => 'instagramcron [<site>]',
                        'defaults' => array(
                            'controller' => 'Instagram\Controller\Instagram',
                            'action' => 'instagramcron'
                        )
                    )
                ),
    			'getinstagramlikes' => array(
    				'options' => array(
    					'route'    => 'getinstagramlikes [<dev>]',
    					'defaults' => array(
    						'controller' => 'Instagram\Controller\Instagram',
    						'action' => 'getlikes'
						)
    				)
    			)
    		),
    	),
    ),

);
