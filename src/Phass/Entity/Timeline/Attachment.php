<?php

namespace Phass\Entity\Timeline;

use Phass\Entity\GlassModelAbstract;

class Attachment extends GlassModelAbstract
{
    /**
     * @var string
     */
    protected $_mimeType;
    
    /**
     * @var string
     */
    protected $_content;
    
    /**
     * @var string
     */
    protected $_contentUrl;
    
    /**
     * @var bool
     */
    protected $_isProcessing;
    /**
     * @var string
     */
    protected $_id;
    
    /**
	 * @return the $_contentUrl
	 */
	public function getContentUrl() {
		return $this->_contentUrl;
	}

	/**
	 * @return the $_isProcessing
	 */
	public function isProcessing() {
		return $this->_isProcessing;
	}

	/**
	 * @param string $_contentUrl
	 * @return self
	 */
	public function setContentUrl($_contentUrl) {
		$this->_contentUrl = $_contentUrl;
		return $this;
	}

	/**
	 * @param boolean $_isProcessing
	 * @return self
	 */
	public function setProcessing($_isProcessing) {
		$this->_isProcessing = (bool)$_isProcessing;
		return $this;
	}

	public function toArray()
	{
	    $retval = array(
	       'id' => $this->getId(),
	       'contentType' => $this->getMimeType(),
	       'contentUrl' => $this->getContentUrl(),
	       'isProcessingContent' => $this->isProcessing()
	    );
	    
	    return $retval;
	}
	
	public function fromJsonResult(array $result)
    {
        $this->setId(isset($result['id']) ? $result['id'] : null)
             ->setMimeType(isset($result['contentType']) ? $result['contentType'] : null)
             ->setContentUrl(isset($result['contentUrl']) ? $result['contentUrl'] : null)
             ->setProcessing(isset($result['isProcessingContent']) ? $result['isProcessingContent'] : null);
        
        return $this;
    }
    /**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @param string $_id
	 * @return self
	 */
	public function setId($_id) {
		$this->_id = $_id;
		return $this;
	}

	public function __construct($content = null, $mimeType = null)
    {
        if(!is_null($content)) {
            $this->setContent($content);
        }
        
        if(!is_null($mimeType)) {
            $this->setMimeType($mimeType);
        }
    }
    
	/**
	 * @return the $_mimeType
	 */
	public function getMimeType() {
		return $this->_mimeType;
	}

	/**
	 * @return the $_content
	 */
	public function getContent() {
		return $this->_content;
	}

	/**
	 * @param string $_mimeType
	 * @return self
	 */
	public function setMimeType($_mimeType) {
		$this->_mimeType = $_mimeType;
		return $this;
	}

	/**
	 * @param string $_content
	 * @return self
	 */
	public function setContent($_content) {
		$this->_content = $_content;
		return $this;
	}

}