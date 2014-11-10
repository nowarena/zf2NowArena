<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Blvd\Controller\Index' => 'Blvd\Controller\IndexController',
            'Blvd\Controller\Admin' => 'Blvd\Controller\AdminController',
        ),
    ),
    'view_helpers' => array(
       'invokables' => array(
		'displaySocialContent' => 'Blvd\View\Helper\SocialContent',
		'displayCategories' => 'Blvd\View\Helper\Categories',
		'displayNews' => 'Blvd\View\Helper\News',
		'streetsign' => 'Blvd\View\Helper\Streetsign',
		'container' => 'Blvd\View\Helper\Container',
		'mngcategory' => 'Blvd\View\Helper\mngcategory',
        'getAdminLinks' => 'Blvd\View\Helper\AdminLinks',
        'AdGlobal' => 'Blvd\View\Helper\AdGlobal'   
		),
	),
	'router' => array(
		'routes' => array(
			'blvd' => array(
				'type'    => 'segment',
				'options' => array(
				    /*http://stackoverflow.com/questions/11536962/routing-in-zend-framework-2-skip-index-action-in-url-but-get-id*/
					'route'    => '/admin[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
						'id'     => '[0-9]*',
					),
					'defaults' => array(
						'controller' => 'Blvd\Controller\Admin',
						'action'     => 'index',
					),
				),
			),
		    'social' => array(
	    		'type'    => 'segment',
	    		'options' => array(
    				'route' => '/social[/:category][/:category_id]',
    				/*'route' => '/atheism[/:category][/:category_id]',*/
    				'constraints' => array(
   						'category' => '[0-9a-zA-Z_-]+',
   						'category_id' => '[0-9]+',
    				),
    				'defaults' => array(
   						'controller' => 'Blvd\Controller\Index',
   						'action'     => 'index'
    				),
	    		),
		    ),
		    'unpublishsocialmedia' => array(
	    		'type' => 'segment',
	    		'options' => array(
    				'route' => '/unpublishsocialmedia[/:social_id][/:username]',
    				'defaults' => array(
   						'controller' => 'Blvd\Controller\Admin',
   						'action' => 'unpublishsocialmedia'
    				),
    				'constraints' => array(
   						'social_id' => '[a-zA-Z0-9_-]+',
   						'username' => '[a-zA-Z0-9_-]+',
    				),
	    		)
		    ),		    
		    'socialmedia' => array(
	    		'type' => 'segment',
	    		'options' => array(
    				'route' => '/socialmedia[/:id][/:offset]',
    				'defaults' => array(
   						'controller' => 'Blvd\Controller\Index',
   						'action' => 'socialmedia'
    				),
    				'constraints' => array(
   						'id' => '[0-9]+',
   						'offset' => '[0-9]+',
    				),
	    		)
		    ),
		    'nextbiz' => array(
	    		'type' => 'segment',
	    		'options' => array(
    				'route' => '/nextbiz[/:category_id][/:offset]',
    				'defaults' => array(
   						'controller' => 'Blvd\Controller\Index',
   						'action' => 'nextbiz'
    				),
    				'constraints' => array(
   						'category_id' => '[0-9]+',
   						'offset' => '[0-9]+',
    				),
	    		)
		    ),
		    'browseblvd' => array(
	    		'type'    => 'segment',
	    		'options' => array(
    				'route' => '/browseblvd',
    				'constraints' => array(
   						'category'     => '[0-9a-zA-Z_-]+',
   						'category_id'     => '[0-9]+',
    				),
    				'defaults' => array(
   						'controller' => 'Blvd\Controller\Index',
   						'action'     => 'index'
    				),
	    		),
		    ),
		),
	),
	'view_manager' => array(
        'template_path_stack' => array(
            'blvd' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'partial' => __DIR__ . '/../view/partial/'
        ),       
	),
);