<?php

namespace Phass\Model;

use Phass\Entity\Credentials;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class CredentialsTable implements FactoryInterface
{
    /**
     * @var  Zend\Db\TableGateway\TableGateway
     */
    protected $_tableGateway;
    
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('Phass\Db\Adapter');
        	
        $resultSetProtoType = new ResultSet();
        $resultSetProtoType->setArrayObjectPrototype(new Credentials());
        	
        $tg = new TableGateway('credentials', $dbAdapter, null, $resultSetProtoType);
        
        $retval = new self();
        $retval->setTableGateway($tg);
        
        return $retval;
    }
    
    /**
	 * @return the $_tableGateway
	 */
	public function getTableGateway() {
		return $this->_tableGateway;
	}

	/**
	 * @param TableGateway $_tableGateway
	 * @return self
	 */
	public function setTableGateway($_tableGateway) {
		$this->_tableGateway = $_tableGateway;
		return $this;
	}

	static public function create(array $data) 
    {
        $retval = new Credentials();
        return $retval->exchangeArray($data);
    }
    
    public function fetchAll()
    {
        return $this->_tableGateway->select();
    }
    
    public function findByUserId($id) 
    {
        $row = $this->_tableGateway->select(array('user_id' => $id));
        
        if(!$row) {
            return null;
        }
        
        return $row;
    }
    
    public function save(Credentials $credentials)
    {
        $data = $credentials->toArray();
        
        if(empty($data['user_id'])) {
            return $this->_tableGateway->insert($data);
        } else {
            if($this->findByUserId($data['user_id'])) {
                return $this->_tableGateway->update($data, array('user_id' => $data['user_id']));
            } else {
                return $this->_tableGateway->insert($data);
            }
        }
    }
    
    public function delete(Credentials $creds) {
        return $this->_tableGateway->delete(array('user_id' => $creds->getUserId()));
    }
}