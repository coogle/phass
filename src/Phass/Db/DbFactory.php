<?php

namespace Phass\Db;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        	
        try {
        	$dbAdapter = $serviceLocator->get($config['phass']['dbAdapter']);
        } catch(\Zend\ServiceManager\Exception\ServiceNotFoundException $e) {
        	throw new \Zend\ServiceManager\Exception\ServiceNotFoundException("You must specify a valid DB Adapter in the Google Glass Module configuration");
        }
        	
        return $dbAdapter;
    }
}