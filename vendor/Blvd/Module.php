<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Blvd for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blvd;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Blvd\Model\BlvdTable;
use Blvd\Model\LinkMapper;
use Blvd\Model\SocialMediaMapper;
use Blvd\Model\BlvdMapper;
use Blvd\Model\BlvdCategoryMapper;
use Blvd\Model\BlvdJoinCategory;
use Blvd\Model\BlvdJoinCategoryTable;
use Blvd\Model\BlvdJoinCategoryMapper;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\View\Model\ViewModel;

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

        //subnav
        $subNavView = new ViewModel();
        $subNavView->setTemplate('Blvd/View/Helper/Subnav');
        $subNavView->addChild($subNavView, 'subNav');
        
        
    }
    
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'Blvd\Model\BlvdTable' =>  function($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$table = new BlvdTable($dbAdapter);
    						return $table;
    					},
    					'Blvd\Model\BlvdMapper' =>  function($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new BlvdMapper($dbAdapter);
    						$mapper->setServiceLocator($sm);
    						return $mapper;
    					},
    					'Blvd\Model\BlvdCategoryMapper' =>  function($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new BlvdCategoryMapper($dbAdapter);
    						return $mapper;
    					},
    					'Blvd\Model\BlvdJoinCategoryMapper' =>  function($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new BlvdJoinCategoryMapper($dbAdapter);
    						return $mapper;
    					},
    					'Blvd\Model\BlvdJoinCategoryTable' =>  function($sm) {
    						$tableGateway = $sm->get('BlvdJoinCategoryTableGateway');
    						$table = new BlvdJoinCategoryTable($tableGateway);
    						return $table;
    					},
    					'BlvdJoinCategoryTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new BlvdJoinCategory());
    						return new TableGateway('blvd_categories', $dbAdapter, null, $resultSetPrototype);
    					},
    					'Blvd\Model\SocialMediaMapper' =>  function($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$mapper = new socialMediaMapper($dbAdapter);
    						$mapper->setServiceLocator($sm);
    						return $mapper;
    					},
    			),
    	);
    }
}
