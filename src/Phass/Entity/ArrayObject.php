<?php

namespace Phass\Entity;

class ArrayObject implements ArrayAccess, Iterator
{
	protected $_data = array();
	protected $_typeLock = null;

	function __construct()
	{
		$this->_data = array();
	}

	public function setType($type) {
		$this->_typeLock = $type;
	}

	public function getType() {
		return $this->_typeLock;
	}

	protected function _isValidType($value) {
		if(is_null($this->_typeLock)) {
			return true;
		}

		$isGood = false;

		$valType = gettype($value);

		if($valType == 'object') {
			$isGood =  $value instanceof $this->_typeLock;
		} else {
			$isGood = ($valType == $this->getType());
			switch($valType) {
				case 'boolean':
					$isGood &=  is_bool($value);
					break;
				case 'integer':
					$isGood &=  is_integer($value);
					break;
				case 'double':
					$isGood &=  is_double($value);
					break;
				case 'string':
					$isGood &=  is_string($value);
					break;
				case 'array':
					$isGood &=  is_array($value);
					break;
				case 'resource':
					$isGood &=  is_resource($value);
					break;
				case 'NULL':
					$isGood &=  is_null($value);
					break;
				default:
					throw new \Exception("Invalid Type from gettype()");
			}
		}

		if(!$isGood) {
			throw new \InvalidArgumentException("This object can only store '{$this->_typeLock}' values.");
		}

		return true;
	}
	public function push($value) {
		$this->_isValidType($value);
		array_push($this->_data, $value);
		return $this;
	}

	public function pop() {
		return array_pop($this->_data);
	}

	public function toArray()
	{
		return $this->_data;
	}

	public function prepend($value)
	{
		$this->_isValidType($value);
		array_unshift($this->_data, $value);
		return $this;
	}

	public function append($value)
	{
		$this->_isValidType($value);
		$this->_data[] = $value;
		return $this;
	}

	public function isEmpty()
	{
		return empty($this->_data);
	}

	public function count()
	{
		return count($this->_data);
	}

	public function current()
	{
		return current($this->_data);
	}

	public function key()
	{
		return key($this->_data);
	}

	public function next()
	{
		return next($this->_data);
	}

	public function rewind()
	{
		return reset($this->_data);
	}

	public function valid()
	{
		return key($this->_data) !== null;
	}

	public function offsetExists($offset)
	{
		return isset($this->_data[$offset]);
	}

	public function offsetGet($offset)
	{
		return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
	}

	public function offsetSet($offset, $value)
	{
		$this->_isValidType($value);

		if(is_null($offset)) {
			$this->_data[] = $value;
		} else {
			$this->_data[$offset] = $value;
		}
	}

	public function offsetUnset($offset)
	{
		unset($this->_data[$offset]);
	}
}