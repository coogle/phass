<?php

namespace Phass\Entity\OAuth2;

class Token extends \OAuth2\Entity\Token
{
    /**
     * @var \GoogleGlass\Entity\OAuth2\Jwt;
     */
    protected $_jwt;
    
    public function __sleep()
    {
        return array(
            '_accessToken', '_refreshToken', '_expiresAt', '_tokenType', '_jwt'
        );
    }
    
    public function __wakeup()
    {
        parent::__wakeup();
        
        if(is_null($this->getJwt())) {
            $this->setJwt(new Jwt());
        }
    }
    /**
     * @return \GoogleGlass\Entity\OAuth2\Jwt
     */
    public function getJwt() {
        return $this->_jwt;
    }

    /**
     * @param \Phass\Entity\OAuth2\JWT $_jwt
     * @return self
     */
    public function setJwt(\Phass\Entity\OAuth2\Jwt $_jwt) {
        $this->_jwt = $_jwt;
        return $this;
    }
    
    /**
     * @param array $result
     * @return self
     */
    public function fromArrayResult(array $result)
    {
        $this->setAccessToken(isset($result['access_token']) ? $result['access_token'] : null)
        ->setRefreshToken(isset($result['refresh_token']) ? $result['refresh_token'] : null)
        ->setTokenType(isset($result['token_type']) ? $result['token_type'] : null);
    
        if(isset($result['id_token'])) {
            $jwtDecoder = $this->getServiceLocator()->get('Phass\OAuth2\JWT');
            $jwtObj = $jwtDecoder->decode($result['id_token']);
            $this->setJwt($jwtObj);
        }
    
        $date = new \DateTime('now');
        $date->add(\DateInterval::createFromDateString("+{$result['expires_in']} seconds"));
    
        $this->setExpiresAt($date);
    
        return $this;
    }
}