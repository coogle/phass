<?php

namespace Phass\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Response;
use Phass\Api\Exception\ApiCallException;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

class AttachmentController extends AbstractActionController
{
    public function getAction()
    {
        $itemId = $this->params('itemId', null);
        $attachmentId = $this->params('attachmentId', null);
        
        if(is_null($itemId) || is_null($attachmentId)) {
            throw new \InvalidArgumentException("Missing necessary IDs to retrieve attachment");
        }
        
        $glassService = $this->getServiceLocator()->get('Phass\Service\GlassService');
        
        if(!$glassService->isAuthenticated()) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            $this->getResponse()->setContent("This user is not authenticated");
            $this->glass()->sendResponse($this->getResponse());
            return;
        }
        
        $token = $this->getServiceLocator()->get('OAuth2\Token');

        $client = $this->getServiceLocator()->get('Phass\Api\Client');
        
        try {
            $response = $client->execute("timeline::attachment::get", compact('itemId', 'attachmentId'));
        } catch(ApiCallException $e) {
            if($e->getCode() == 404) {
                $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
                $this->getResponse()->setContent("Could not locate resource");
                return;
            }
        }
        
        if($response->isProcessing()) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_503)
                                ->setContent("The requested Resource is not yet available. Try again later.")
                                ->setHeader("Retry-After", 60); // 60 seconds
            return;
        }
        
        $contextConfig = array(
            'http' => array(
                'method' => 'GET',
                'header' => "Authorization: {$token->getTokenType()} {$token->getAccessToken()}" 
            )
        );
        
        $context = stream_context_create($contextConfig);
        
        $contentFile = fopen($response->getContentUrl(), 'r', false, $context);
        
        if(!$contentFile) {
            throw new \RuntimeException("Failed to open content URL for attachment");
        }
        
        $streamResponse = new Stream();
        
        $streamResponse->setStream($contentFile)
                       ->setStreamName($response->getContentUrl())
                       ->setStatusCode(Response::STATUS_CODE_200);
        
        $headers = new Headers();
        
        $headers->addHeaders(array(
            'Content-Type' => $response->getMimeType()
        ));
        
        $streamResponse->setHeaders($headers);
        
        return $streamResponse;
    }
}