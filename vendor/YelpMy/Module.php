<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Yelp for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace YelpMy;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use YelpMy\Model\YelpMapper;
use YelpMy\Model\YelpBlvd;
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
				'YelpMy\Model\YelpMapper' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$mapper = new YelpMapper($dbAdapter);
					return $mapper;
				},
				'YelpMy\Model\YelpBlvd' => function ($sm) {
				    $configArr = $sm->get('config');
				    $model = new YelpBlvd($configArr['yelp']);
				    return $model;   
				},
				'Blvd\Model\BlvdMapper' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$model = new BlvdMapper($dbAdapter);
					$model->setServiceLocator($sm);
					return $model;
				},
				'Blvd\Model\SocialMediaMapper' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$model = new SocialMediaMapper($dbAdapter);
					$model->setServiceLocator($sm);
					return $model;
				},
    		),
    	);
    }
     
}
