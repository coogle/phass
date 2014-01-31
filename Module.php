<?php

namespace Phass;

use Zend\Mvc\MvcEvent;
use Phass\Api\Exception\InvalidTokenException;
use Zend\Http\Response;
use CoogleLib\ServiceManager\ServiceLocatorFactory;

class Module
{
    /**
     * This method listens for a Dispatching error and checks to see if
     * the error was caused by us not having a valid token. If it is, we auto
     * magically redirect the user into the OAuth2 workflow to reauth.
     * 
     * @param MvcEvent $e
     */
    public function onDispatchError(MvcEvent $e)
    {
        $serviceLocator = $e->getApplication()->getServiceManager();
        $config = $serviceLocator->get('Config');
        
        $exception = $e->getParam('exception');
        
        if(!$exception instanceof \Exception)
        {
            return;
        }
        
        $previousException = $exception->getPrevious();
        
        if((!$exception instanceof InvalidTokenException) && (!$previousException instanceof \Exception))
        {
            return;
        }
        
        while($previousException) {
            if($previousException instanceof InvalidTokenException)
            {
                if(is_null($config['oauth2']['auth']['redirect_uri'])) {
                    $router = $serviceLocator->get('Router');
        
                    $requestUri = $router->getRequestUri();
                    $requestUri->setQuery(null);
        
                    $OAuthUrl = $router->assemble(array(), array(
                            'name' => 'oauth2-callback',
                            'force_canonical' => true,
                            'uri' => $requestUri
                    ));
                } else {
                    $OAuthUrl = $config['oauth2']['auth']['redirect_uri'];
                }
        
                $response = $e->getResponse();
                $response->setStatusCode(Response::STATUS_CODE_302)
                         ->getHeaders()
                         ->addHeaderLine('Location', $OAuthUrl);
        
                $e->setResponse($response);
                return;
            }
            $previousException = $previousException->getPrevious();
        }
        
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 100); 
        
        $sharedManager = $eventManager->getSharedManager();
        $sharedManager->attachAggregate($e->getApplication()->getServiceManager()->get('Phass\Notifications\Listener'));
        
        ServiceLocatorFactory::setInstance($e->getApplication()->getServiceManager());
    }
    
    public function getAutoloaderConfig()
    {
        return array(
                'Zend\Loader\ClassMapAutoloader' => array(
                        __DIR__ . '/autoload_classmap.php'
                ),
                'Zend\Loader\StandardAutoloader' => array(
                        'namespaces' => array(
                                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                        )
                )
        );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Phass\Timeline\Item' => 'Phass\Entity\Timeline\Item',
                'Phass\Timeline\Attachment' => 'Phass\Entity\Timeline\Attachment',
                'Phass\Timeline\MenuItem' => 'Phass\Entity\Timeline\MenuItem',
                'Phass\Timeline\NotificationConfig' => 'Phass\Entity\Timeline\NotificationConfig',
                'Phass\Contact' => 'Phass\Entity\Contact',
                'Phass\Location' => 'Phass\Entity\Location',
                'Phass\Timeline' => 'Phass\Entity\Timeline',
                'Phass\Service\GlassService' => 'Phass\Service\GlassServiceFactory',
                'Phass\Db\Adapter' => 'Phass\Db\DbFactory',
                'Phass\Model\CredentialsTable' => 'Phass\Model\CredentialsTable',
                'Phass\OAuth2\Jwt' => 'Phass\OAuth2\Jwt\Jwt',
                'Phass\Api\Client' => 'Phass\Api\ClientFactory',
                'Phass\Subscription' => 'Phass\Entity\Subscription',
                'Phass\Notifications\Listener' => 'Phass\Notifications\NotificationsListenerFactory'
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'getItemImageUrl' => 'Phass\View\Helper\GetItemImageUrl'
            )
        );
    }
    
    public function getModuleDependencies()
    {
        return array('CoogleLib', 'OAuth2');
    }
}