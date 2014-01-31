<?php

namespace Phass\Entity\OAuth2;

class Jwt
{
    /**
     * @var string
     */
    protected $_issuerId;
    
    /**
     * @var string
     */
    protected $_accessTokenHash;
    
    /**
     * @var bool
     */
    protected $_emailVerified;
    
    /**
     * @var string
     */
    protected $_uniqueId;
    
    /**
     * @var string
     */
    protected $_authorizedPresenter;
    
    /**
     * @var string
     */
    protected $_email;
    
    /**
     * @var string
     */
    protected $_audience;
    
    /**
     * @var \DateTime
     */
    protected $_issuedAt;
    
    /**
     * @var \DateTime
     */
    protected $_expiresAt;
    
    public function __sleep()
    {
        return array(
            '_issuerId', '_accessTokenHash', '_emailVerified', '_uniqueId',
            '_authorizedPresenter', '_email', '_audience', '_issuedAt', '_expiresAt'
        );
    }
    
    /**
     * @return the $_issuerId
     */
    public function getIssuerId() {
        return $this->_issuerId;
    }

    /**
     * @return the $_accessTokenHash
     */
    public function getAccessTokenHash() {
        return $this->_accessTokenHash;
    }

    /**
     * @return the $_emailVerified
     */
    public function getEmailVerified() {
        return $this->_emailVerified;
    }

    /**
     * @return the $_uniqueId
     */
    public function getUniqueId() {
        return $this->_uniqueId;
    }

    /**
     * @return the $_authorizedPresenter
     */
    public function getAuthorizedPresenter() {
        return $this->_authorizedPresenter;
    }

    /**
     * @return the $_email
     */
    public function getEmail() {
        return $this->_email;
    }

    /**
     * @return the $_audience
     */
    public function getAudience() {
        return $this->_audience;
    }

    /**
     * @return the $_issuedAt
     */
    public function getIssuedAt() {
        return $this->_issuedAt;
    }

    /**
     * @return the $_expiresAt
     */
    public function getExpiresAt() {
        return $this->_expiresAt;
    }

    /**
     * @param string $_issuerId
     * @return self
     */
    public function setIssuerId($_issuerId) {
        $this->_issuerId = $_issuerId;
        return $this;
    }

    /**
     * @param string $_accessTokenHash
     * @return self
     */
    public function setAccessTokenHash($_accessTokenHash) {
        $this->_accessTokenHash = $_accessTokenHash;
        return $this;
    }

    /**
     * @param boolean $_emailVerified
     * @return self
     */
    public function setEmailVerified($_emailVerified) {
        $this->_emailVerified = $_emailVerified;
        return $this;
    }

    /**
     * @param string $_uniqueId
     * @return self
     */
    public function setUniqueId($_uniqueId) {
        $this->_uniqueId = $_uniqueId;
        return $this;
    }

    /**
     * @param string $_authorizedPresenter
     * @return self
     */
    public function setAuthorizedPresenter($_authorizedPresenter) {
        $this->_authorizedPresenter = $_authorizedPresenter;
        return $this;
    }

    /**
     * @param string $_email
     * @return self
     */
    public function setEmail($_email) {
        $this->_email = $_email;
        return $this;
    }

    /**
     * @param string $_audience
     * @return self
     */
    public function setAudience($_audience) {
        $this->_audience = $_audience;
        return $this;
    }

    /**
     * @param DateTime $_issuedAt
     * @return self
     */
    public function setIssuedAt($_issuedAt) {
        if(!is_null($_issuedAt)) {
            $this->_issuedAt = \DateTime::createFromFormat('U', $_issuedAt);
        }
        
        return $this;
    }

    /**
     * @param DateTime $_expiresAt
     * @return self
     */
    public function setExpiresAt($_expiresAt) {
        if(!is_null($_expiresAt)) {
            $this->_expiresAt = \DateTime::createFromFormat('U', $_expiresAt);
        }
        
        return $this;
    }

    static public function createFromArray(array $input)
    {
        $retval = new static();
        
        $retval->setIssuerId(isset($input['iss']) ? $input['iss'] : null)
               ->setAccessTokenHash(isset($input['at_hash']) ? $input['at_hash'] : null)
               ->setAudience(isset($input['aud']) ? $input['aud'] : null)
               ->setAuthorizedPresenter(isset($input['azp']) ? $input['azp'] : null)
               ->setEmail(isset($input['email']) ? $input['email'] : null)
               ->setEmailVerified(isset($input['email_verified']) ? (bool)$input['email_verified'] : null)
               ->setExpiresAt(isset($input['exp']) ? $input['exp'] : null)
               ->setIssuedAt(isset($input['iat']) ? $input['iat'] : null)
               ->setUniqueId(isset($input['sub']) ? $input['sub'] : null);
        
        return $retval;
    }
}