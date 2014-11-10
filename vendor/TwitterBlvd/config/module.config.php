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
            'twitterblvd' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/twitterblvd/twitter[/:action][/:screenname][/:site]',
                    'constraints' => array(
                    		'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    		'screenname' => '[0-9a-zA-Z_]*',
                    ),
                    'defaults' => array(
                        'controller'    => 'TwitterBlvd\Controller\Twitter',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
    		'invokables' => array(
    				'TwitterBlvd\Controller\Twitter' => 'TwitterBlvd\Controller\TwitterController'
    		),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'twitterblvd' => __DIR__ . '/../view',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'twittercronroute' => array( /*twittercronroute must be unique across all module.config.php files */
                    'options' => array(
                    	'route'    => 'twittercron [<dev>]',
                    	'defaults' => array(
                    	   'controller' => 'TwitterBlvd\Controller\Twitter',
                    	   'action' => 'twittercron'
                    	)
                    )
                )
            ),
        ),
    ),
);
