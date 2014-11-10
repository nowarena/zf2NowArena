<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Twitter for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Twitter;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Twitter\Model\TwitterUserMapper;
use Twitter\Model\TweetMapper;
use Twitter\Model\TwitterBlvd;
use Blvd\Model\BlvdMapper;
use Gallery\Model\GalleryMapper;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		            // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'Twitter\Model\TwitterBlvd' => function ($sm) {
    					    $configArr = $sm->get('config');
    					    $twitterBlvd = new TwitterBlvd($configArr['twitter']);
    					    $twitterBlvd->setUserId($configArr['twitter']['twitterUser']['id']);
    					    $twitterBlvd->setScreenName($configArr['twitter']['twitterUser']['screen_name']);
    					    return $twitterBlvd;
    					},
    					'Gallery\Model\GalleryMapper' =>  function($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new GalleryMapper($dbAdapter);
    						return $mapper;
    					},
    			),
    	);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        
        
        
    }
}
