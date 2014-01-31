<?php

namespace Phass\OAuth2\Jwt;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Json\Json;
use Phass\Entity\OAuth2\Jwt as JwtEntity;

if(class_exists('\Phass\OAuth2\Jwt\Jwt', false)) {
    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    exit;
}

class Jwt implements ServiceLocatorAwareInterface, FactoryInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    use \Phass\Entity\SimpleFactoryTrait;
    
    public function decode($jwt)
    {
        $segments = explode('.', $jwt);
        
        if(count($segments) != 3) {
            throw new \UnexpectedValueException("Wrong number of segments");
        }
        
        list($headerB64, $bodyB64, $sigB64) = $segments;
        
        $header = Json::decode($this->base64Decode($headerB64), Json::TYPE_ARRAY);
        $content = Json::decode($this->base64Decode($bodyB64), Json::TYPE_ARRAY);
        
        $jwtObj = JwtEntity::createFromArray($content);
        
        return $jwtObj;
    }
    
    protected function base64Decode($input)
    {
        $r = strlen($input) % 4;
        
        if($r > 0) {
            $pad = 4 - $r;
            $input .= str_repeat('=', $pad);
        }
        
        return base64_decode(strtr($input, '-_', '+/'));
    }
    
}