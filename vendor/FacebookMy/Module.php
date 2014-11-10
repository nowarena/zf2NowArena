<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Facebook for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace FacebookMy;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use FacebookMy\Model\FacebookMapper;
use FacebookMy\Model\FacebookUserMapper;
use FacebookMy\Model\FacebookBlvd;
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

    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'Facebook\Model\FacebookMapper' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new FacebookMapper($dbAdapter);
    						$mapper->setServiceLocator($sm);
    						return $mapper;
    					},
    					'Facebook\Model\FacebookUserMapper' => function ($sm) {
    						//$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$dbAdapter = $sm->get('Application\Db\AdapterTwo');
    						$mapper = new FacebookUserMapper($dbAdapter);
    						return $mapper;
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
    					'Facebook\Model\FacebookBlvd' => function ($sm) {
    						$blvd = new FacebookBlvd();
    						$blvd->setServiceLocator($sm);
    						$configArr = $sm->get('config');
    						$blvd->setAppId($configArr['facebook']['appId']);
    						$blvd->setAppSecret($configArr['facebook']['appSecret']);
    						return $blvd;
    					},
    			),
    	);
    } 

}//
