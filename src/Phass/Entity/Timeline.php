<?php

namespace Phass\Entity;

class Timeline extends GlassModelAbstract implements \ArrayAccess
{
    /**
     * @var string
     */
    protected $_kind;
    
    /**
     * @var string
     */
    protected $_nextPageToken;
    
    /**
     * @var \ArrayObject
     */
    protected $_items;
    
    public function __construct()
    {
        $this->_items = new \ArrayObject();
    }
    
    public function items()
    {
        return $this->_items->getIterator();
    }
    
    public function offsetExists($offset)
    {
        return $this->_items->offsetExists($offset);
    }
    
    public function offsetGet($offset)
    {
        return $this->_items->offsetGet($offset);
    }
    
    public function offsetSet($offset, $value)
    {
        return $this->_items->offsetSet($offset, $value);
    }
    
    public function offsetUnset($offset)
    {
        return $this->_items->offsetUnset($offset);
    }
    
    public function toArray()
    {
        $retval = array(
            'kind' => $this->getKind(),
            'nextPageToken' => $this->getNextPageToken()
        );
        
        $retval['items'] = array();
        
        foreach($this->items() as $item) {
            $retval['items'] = $item->toArray();
        }
        
        return $retval;
    }
    
    public function fromJsonResult(array $result)
    {
        $this->setKind($result['kind'])
             ->setNextPageToken($result['nextPageToken']);
        
        foreach($result['items'] as $itemResult)
        {
            $item = $this->getServiceLocator()->get('Phass\Timeline\Item');
            $item->fromJsonResult($itemResult);
            
            $this->_items->append(clone $item);
        }
        
        
        return $this;
    }
    
	/**
	 * @return the $_kind
	 */
	public function getKind() {
		return $this->_kind;
	}

	/**
	 * @return the $_nextPageToken
	 */
	public function getNextPageToken() {
		return $this->_nextPageToken;
	}

	/**
	 * @return the $_items
	 */
	public function getItems() {
		return $this->_items;
	}

	/**
	 * @param string $_kind
	 * @return self
	 */
	public function setKind($_kind) {
		$this->_kind = $_kind;
		return $this;
	}

	/**
	 * @param string $_nextPageToken
	 * @return self
	 */
	public function setNextPageToken($_nextPageToken) {
		$this->_nextPageToken = $_nextPageToken;
		return $this;
	}

	/**
	 * @param ArrayObject $_items
	 * @return self
	 */
	public function setItems($_items) {
		$this->_items = $_items;
		return $this;
	}

}