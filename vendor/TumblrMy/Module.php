<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/TumblrMy for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace TumblrMy;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use TumblrMy\Model\TumblrMapper;
use TumblrMy\Model\TumblrBlvd;
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
   				'TumblrMy\Model\TumblrMapper' => function ($sm) {
   					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
   					$mapper = new TumblrMapper($dbAdapter);
   					return $mapper;
   				},
   				'TumblrMy\Model\TumblrBlvd' => function ($sm) {
   					$configArr = $sm->get('config');
   					$TumblrBlvd = new TumblrBlvd($configArr['tumblr']);
   					return $TumblrBlvd;
   				},
		    	'Gallery\Model\GalleryMapper' =>  function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$mapper = new GalleryMapper($dbAdapter);
					return $mapper;
				},
    		),
    	);
    } 
}
