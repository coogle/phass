<?php

namespace Phass\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GlassServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sm)
    {
        $client = $sm->get('Phass\Api\Client');
        $service = new GlassService();
        $service->setGlassApiClient($client);
        
        return $service;
    }
}