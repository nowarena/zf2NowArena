<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonTwitter for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(


    'router' => array(
        'routes' => array(
            'twitter' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/twitter[/:action][/:screenname][/:site]',
                    'constraints' => array(
                    		'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    		'site'     => '[0-9a-zA-Z_]*',
                    		'screenname' => '[0-9a-zA-Z_]*',
                    ),
                    'defaults' => array(
                        'controller'    => 'Twitter\Controller\Twitter',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
    		'invokables' => array(
    				'Twitter\Controller\Twitter' => 'Twitter\Controller\TwitterController'
    		),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'twitter' => __DIR__ . '/../view',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'gettwitterfavorites' => array( /*twittercronroute must be unique across all module.config.php files */
                    'options' => array(
                    	'route'    => 'gettwitterfavorites [<dev>]',
                    	'defaults' => array(
                    	   'controller' => 'Twitter\Controller\Twitter',
                    	   'action' => 'getfavorites'
                    	)
                    )
                )
            ),
        ),
    ),
);
