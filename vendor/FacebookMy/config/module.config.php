<?php
/*

 */
return array(
    'controllers' => array(
        'invokables' => array(
            'FacebookMy\Controller\Facebook' => 'FacebookMy\Controller\FacebookController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'facebook' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/facebook',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'FacebookMy\Controller',
                        'controller'    => 'Facebook',
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
    // Placeholder for console routes
    'console' => array(
    		'router' => array(
    				'routes' => array(
    						'facebookpagescronroute' => array(
    								'options' => array(
    										'route'    => 'facebookpagescron [<dev>]',
    										'defaults' => array(
    												'controller' => 'FacebookMy\Controller\Facebook',
    												'action' => 'facebookpagescron'
    										)
    								)
    						)
    				),
    		),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Facebook' => __DIR__ . '/../view',
        ),
    ),
);
