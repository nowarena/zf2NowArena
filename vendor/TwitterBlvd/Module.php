<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Twitter for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TwitterBlvd;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use TwitterBlvd\Model\TwitterUserMapper;
use TwitterBlvd\Model\TweetMapper;
use TwitterBlvd\Model\TwitterBlvd;
use Blvd\Model\BlvdMapper;
use Blvd\Model\SocialMediaMapper;

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
    					'TwitterBlvd\Model\TwitterUserMapper' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new TwitterUserMapper($dbAdapter);
    						return $mapper;
    					},
    					'TwitterBlvd\Model\TweetMapper' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new TweetMapper($dbAdapter);
    						$mapper->setServiceLocator($sm);
    						return $mapper;
    					},
    					'TwitterBlvd\Model\TwitterBlvd' => function ($sm) {
    					    $configArr = $sm->get('config');
    					    $twitterBlvd = new TwitterBlvd($configArr['twitter']);
    					    $twitterBlvd->setUserId($configArr['twitter']['twitterUser']['id']);
    					    $twitterBlvd->setScreenName($configArr['twitter']['twitterUser']['screen_name']);
    					    return $twitterBlvd;
    					},
    					
    					'Blvd\Model\BlvdMapper' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new BlvdMapper($dbAdapter);
    						return $mapper;
    					},
    					'Blvd\Model\SocialMediaMapper' =>  function($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new SocialMediaMapper($dbAdapter);
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
