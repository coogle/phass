<?php

return array(
    'controller_plugins' => array(
       'invokables' => array(
           'Phass' => 'Phass\Mvc\Controller\Plugin\Glass',
           'Params' => 'Phass\Mvc\Controller\Plugin\Params'
        )
    ),
    'router' => array(
        'routes' => array(
            'phass-subscription-callback' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/phass/subscription',
                    'defaults' => array(
                        'controller' => 'Phass\Controller\Callback',
                        'action'     => 'subscriptionCallback',
                    ),
                )
            ),
            'phass-attachment-proxy-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/phass/attachment[/:itemId][/:attachmentId]',
                    'defaults' => array(
                        'controller' => 'Phass\Controller\Attachment',
                        'action' => 'get'
                    )
                )
            ),
        )
    ),
    'oauth2' => array(
        'auth' => array(
            "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            "token_uri" => "https://accounts.google.com/o/oauth2/token",
            'scopes' => array(
                'https://www.googleapis.com/auth/glass.timeline',
                'https://www.googleapis.com/auth/glass.location',
                'https://www.googleapis.com/auth/userinfo.profile'
            )
        ),
        'httpClient' => array(
            'useragent' => 'Phass Client',
            'sslcapath' => __DIR__ . '/certs/'
        )
    ),
    'phass' => array(
        'subscriptionController' => null,
        'applicationName' => null,
        'subscriptionUri' => null,
        'randomKey' => 'KJ9#)NDIEOUEIJKL',
        'development' => true,
        'template_path' => null
    ),
    'controllers' => array(
        'invokables' => array(
            'Phass\Controller\Callback' => 'Phass\Controller\CallbackController',
            'Phass\Controller\Attachment' => 'Phass\Controller\AttachmentController'
        ),
    )
);