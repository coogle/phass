<?php

namespace Phass\Entity;

class Credentials 
{
    /**
     * @var string
     */
    protected $_userId;
    
    /**
     * @var string
     */
    protected $_accessToken;
    
    /**
     * @param array $data
     * @return \Phass\Entity\Credentials
     */
    public function exchangeArray(array $data)
    {
        $this->setAccessToken(isset($data['token']) ? $data['token'] : null)
             ->setUserId(isset($data['user_id']) ? $data['user_id'] : null);
        
        return $this;
    }
    
    public function toArray()
    {
        return array(
            'user_id' => $this->getUserId(),
            'token' => $this->getAccessToken()
        );
    }

    /**
	 * @return the $_userId
	 */
	public function getUserId() {
		return $this->_userId;
	}

	/**
	 * @return the $_accessToken
	 */
	public function getAccessToken() {
		return $this->_accessToken;
	}

	/**
	 * @param string $_userId
	 * @return self
	 */
	public function setUserId($_userId) {
		$this->_userId = $_userId;
		return $this;
	}

	/**
	 * @param string $_accessToken
	 * @return self
	 */
	public function setAccessToken($_accessToken) {
		$this->_accessToken = $_accessToken;
		return $this;
	}

}