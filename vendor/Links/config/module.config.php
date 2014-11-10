<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Links\Controller\Index' => 'Links\Controller\IndexController',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'getAdminLinks' => 'Links\View\Helper\AdminLinks',
            'displayLinks' => 'Links\View\Helper\Links'
		),
	),
    
    'router' => array(
        'routes' => array(
            'links' => array(
                'type'    => 'Segment',
                'options' => array(                     
                    'route'    => '/links/[:controller[/:action][/:id]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Links\Controller',
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
            'Links' => __DIR__ . '/../view',
        ),
    ),
);
