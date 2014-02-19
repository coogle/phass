<?php

namespace Phass\Entity\Timeline;

use Phass\Entity\ArrayObject;
use Phass\Entity\GlassModelAbstract;
use Phass\Entity\Timeline\NotificationConfig;
use Phass\Entity\Location;
use Zend\View\Model\ViewModel;
use Zend\View\View;
use Zend\Http\PhpEnvironment\Response;
use Phass\Api\Exception\ApiCallException;

class Item extends GlassModelAbstract implements \ArrayAccess
{
    /**
     * @var \ArrayObject
     */
    protected $_attachments;
    
    /**
     * @var string
     */
    protected $_bundleId;
    
    /**
     * @var \Zend\Uri\Uri
     */
    protected $_canonicalUrl;
    
    /**
     * @var \DateTime
     */
    protected $_created;
    
    /**
     * @var Phass\Contact
     */
    protected $_creator;
    
    /**
     * @var \DateTime
     */
    protected $_displayTime;
    
    /**
     * @var string
     */
    protected $_eTag;
    
    /**
     * @var string
     */
    protected $_html;
    
    /**
     * @var string
     */
    protected $_id;
    
    /**
     * @var string
     */
    protected $_inReplyTo;
    
    /**
     * @var boolean
     */
    protected $_isBundleCover;
    
    /**
     * @var boolean
     */
    protected $_isDeleted;
    
    /**
     * @var booleam
     */
    protected $_isPinned;
    
    /**
     * @var string
     */
    protected $_kind = "mirror#timelineItem";
    
    /**
     * @var Phass\Entity\Location
     */
    protected $_location;
    
    /**
     * @var \ArrayObject
     */
    protected $_menuItems;
    
    /**
     * @var Phass\Entity\NotificationConfig
     */
    protected $_notification;
    
    /**
     * @var int
     */
    protected $_pinScore;
    
    /**
     * @var \ArrayObject
     */
    protected $_recipients;
    
    /**
     * @var string
     */
    protected $_selfLink;
    
    /**
     * @var string
     */
    protected $_sourceItemId;
    
    /**
     * @var string
     */
    protected $_speakableText;
    
    /**
     * @var string
     */
    protected $_speakableType;
    
    /**
     * @var string
     */
    protected $_text;
    
    /**
     * @var string
     */
    protected $_title;
    
    /**
     * @var \Zend\View\Model\ViewModel
     */
    protected $_viewModel;
    
    /**
     * @var \DateTime
     */
    protected $_updated;
    
    /**
     * @var string
     */
    protected $_template;
    
    /**
     * @return the $_template
     */
    public function getTemplate() {
        return $this->_template;
    }

    /**
     * @param string $_template
     * @return self
     */
    public function setTemplate($_template) {
        $this->_template = $_template;
        return $this;
    }

    public function setVariable($var, $val)
    {
        $this->getViewModel()->setVariable($var, $val);
        return $this;
    }
    
    public function getVariable($var)
    {
        return $this->getViewModel()->getVariable($var);
    }
    
    public function render(array $variables = array())
    {
        $config = $this->getServiceLocator()->get('Config');
        
        $templatePath = $config['googleglass']['template_path'];
        
        $template = $this->getTemplate();
        
        if(empty($template)) {
            throw new \RuntimeException("You must provide a template using setTemplate()");
        }
        
        if(is_null($templatePath)) {
            throw new \RuntimeException("You must provide a template path in the configuration");
        }
        
        $viewModel = $this->getViewModel();
        $viewModel->setVariables($variables);
        $viewModel->setTemplate("$templatePath/$template.phtml");
        
        $view = clone $this->getServiceLocator()->get('View');
        $view->setRequest(clone $this->getServiceLocator()->get('Application')->getRequest());
        $view->setResponse(new Response());
        
        $view->render($viewModel);
        
        $html = $view->getResponse()->getContent();
        
        $this->setHtml($html);
    }
    
    public function offsetExists ($offset) {
        return isset($this->getViewModel()->$offset);
    }
    
    /**
     * @param offset
     */
    public function offsetGet ($offset) {
        return $this->getViewModel()->$offset;
    }
    
    /**
     * @param offset
     * @param value
     */
    public function offsetSet ($offset, $value) {
        $this->getViewModel()->$offset = $value;
    }
    
    /**
     * @param offset
     */
    public function offsetUnset ($offset) {
        unset($this->getViewModel()->$offset);
    }
    
    /**
     * @return the $_viewModel
     */
    public function getViewModel() {
        return $this->_viewModel;
    }

    /**
     * @param \Zend\View\Model\ViewModel $_viewModel
     * @return self
     */
    public function setViewModel(\Zend\View\Model\ViewModel $_viewModel) {
        $this->_viewModel = $_viewModel;
        return $this;
    }

    public function __construct()
    {
        $this->_attachments = new \ArrayObject();
        $this->_recipients = new \ArrayObject();
        $this->_menuItems  = new \ArrayObject();
        $this->_viewModel = new ViewModel();
    }
    
    /**
     * @return the $_attachments
     */
    public function getAttachments() {
        return $this->_attachments;
    }

    /**
     * @return the $_bundleId
     */
    public function getBundleId() {
        return $this->_bundleId;
    }

    /**
     * @return the $_canonicalUrl
     */
    public function getCanonicalUrl() {
        return $this->_canonicalUrl;
    }

    /**
     * @return the $_created
     */
    public function getCreated() {
        return $this->_created;
    }

    /**
     * @return the $_creator
     */
    public function getCreator() {
        return $this->_creator;
    }

    /**
     * @return the $_displayTime
     */
    public function getDisplayTime() {
        return $this->_displayTime;
    }

    /**
     * @return the $_eTag
     */
    public function getETag() {
        return $this->_eTag;
    }

    /**
     * @return the $_html
     */
    public function getHtml() {
        return $this->_html;
    }

    /**
     * @return the $_id
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * @return the $_inReplyTo
     */
    public function getInReplyTo() {
        return $this->_inReplyTo;
    }

    /**
     * @return the $_isBundleCover
     */
    public function isBundleCover() {
        return $this->_isBundleCover;
    }

    /**
     * @return the $_isDeleted
     */
    public function isDeleted() {
        return $this->_isDeleted;
    }

    /**
     * @return the $_isPinned
     */
    public function isPinned() {
        return $this->_isPinned;
    }

    /**
     * @return the $_kind
     */
    public function getKind() {
        return $this->_kind;
    }

    /**
     * @return the $_location
     */
    public function getLocation() {
        return $this->_location;
    }

    /**
     * @return the $_menuItems
     */
    public function getMenuItems() {
        return $this->_menuItems;
    }

    /**
     * @return the $_notification
     */
    public function getNotification() {
        return $this->_notification;
    }

    /**
     * @return the $_pinScore
     */
    public function getPinScore() {
        return $this->_pinScore;
    }

    /**
     * @return the $_recipients
     */
    public function getRecipients() {
        return $this->_recipients;
    }

    /**
     * @return the $_selfLink
     */
    public function getSelfLink() {
        return $this->_selfLink;
    }

    /**
     * @return the $_sourceItemId
     */
    public function getSourceItemId() {
        return $this->_sourceItemId;
    }

    /**
     * @return the $_speakableText
     */
    public function getSpeakableText() {
        return $this->_speakableText;
    }

    /**
     * @return the $_speakableType
     */
    public function getSpeakableType() {
        return $this->_speakableType;
    }

    /**
     * @return the $_text
     */
    public function getText() {
        return $this->_text;
    }

    /**
     * @return the $_title
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * @return the $_updated
     */
    public function getUpdated() {
        return $this->_updated;
    }

    /**
     * @param ArrayObject $_attachments
     * @return self
     */
    public function setAttachments($_attachments) {
        $this->_attachments = $_attachments;
        return $this;
    }

    /**
     * @param string $_bundleId
     * @return self
     */
    public function setBundleId($_bundleId) {
        $this->_bundleId = $_bundleId;
        return $this;
    }

    /**
     * @param \Zend\Uri\Uri|string $_canonicalUrl
     * @return self
     */
    public function setCanonicalUrl($_canonicalUrl) {
        $this->_canonicalUrl = $_canonicalUrl;
        return $this;
    }

    /**
     * @param DateTime $_created
     * @return self
     */
    public function setCreated($_created) {
        $this->_created = $_created;
        return $this;
    }

    /**
     * @param \Phass\Entity\Timeline\Phass\Contact $_creator
     * @return self
     */
    public function setCreator($_creator) {
        $this->_creator = $_creator;
        return $this;
    }

    /**
     * @param DateTime $_displayTime
     * @return self
     */
    public function setDisplayTime($_displayTime) {
        $this->_displayTime = $_displayTime;
        return $this;
    }

    /**
     * @param string $_eTag
     * @return self
     */
    public function setETag($_eTag) {
        $this->_eTag = $_eTag;
        return $this;
    }

    /**
     * @param string $_html
     * @return self
     */
    public function setHtml($_html) {
        $this->_html = $_html;
        return $this;
    }

    /**
     * @param string $_id
     * @return self
     */
    public function setId($_id) {
        $this->_id = $_id;
        return $this;
    }

    /**
     * @param string $_inReplyTo
     * @return self
     */
    public function setInReplyTo($_inReplyTo) {
        $this->_inReplyTo = $_inReplyTo;
        return $this;
    }

    /**
     * @param boolean $_isBundleCover
     * @return self
     */
    public function setBundleCover($_isBundleCover) {
        $this->_isBundleCover = (bool)$_isBundleCover;
        return $this;
    }

    /**
     * @param boolean $_isDeleted
     * @return self
     */
    public function setDeleted($_isDeleted) {
        $this->_isDeleted = (bool) $_isDeleted;
        return $this;
    }

    /**
     * @param \Phass\Entity\Timeline\booleam $_isPinned
     * @return self
     */
    public function setPinned($_isPinned) {
        $this->_isPinned = (bool)$_isPinned;
        return $this;
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
     * @param \Phass\Entity\Location $_location
     * @return self
     */
    public function setLocation($_location) {
        $this->_location = $_location;
        return $this;
    }

    /**
     * @param ArrayObject $_menuItems
     * @return self
     */
    public function setMenuItems($_menuItems) {
        
        if(is_array($_menuItems)) {
            $_menuItems = new \ArrayObject($_menuItems);
        } elseif(!$_menuItems instanceof \ArrayObject) {
            throw new \InvalidArgumentException("Must be an array or ArrayObject");
        }
        
        $this->_menuItems = $_menuItems;
        return $this;
    }

    /**
     * @param \Phass\Entity\NotificationConfig $_notification
     * @return self
     */
    public function setNotification(NotificationConfig $_notification) {
        $this->_notification = $_notification;
        return $this;
    }

    /**
     * @param number $_pinScore
     * @return self
     */
    public function setPinScore($_pinScore) {
        $this->_pinScore = $_pinScore;
        return $this;
    }

    /**
     * @param ArrayObject $_recipients
     * @return self
     */
    public function setRecipients($_recipients) {
        $this->_recipients = $_recipients;
        return $this;
    }

    /**
     * @param string $_selfLink
     * @return self
     */
    public function setSelfLink($_selfLink) {
        $this->_selfLink = $_selfLink;
        return $this;
    }

    /**
     * @param string $_sourceItemId
     * @return self
     */
    public function setSourceItemId($_sourceItemId) {
        $this->_sourceItemId = $_sourceItemId;
        return $this;
    }

    /**
     * @param string $_speakableText
     * @return self
     */
    public function setSpeakableText($_speakableText) {
        $this->_speakableText = $_speakableText;
        return $this;
    }

    /**
     * @param string $_speakableType
     * @return self
     */
    public function setSpeakableType($_speakableType) {
        $this->_speakableType = $_speakableType;
        return $this;
    }

    /**
     * @param string $_text
     * @return self
     */
    public function setText($_text) {
        $this->_text = $_text;
        return $this;
    }

    /**
     * @param string $_title
     * @return self
     */
    public function setTitle($_title) {
        $this->_title = $_title;
        return $this;
    }

    /**
     * @param DateTime $_updated
     * @return self
     */
    public function setUpdated($_updated) {
        $this->_updated = $_updated;
        return $this;
    }
    
    public function hasAttachments()
    {
        return count($this->getAttachments()) > 0;
    }

    public function toArray()
    {
        $retval = array(
           'kind' => $this->getKind(),
            'id' => $this->getId(),
            'selfLink' => $this->getSelfLink(),
            'etag' => $this->getETag(),
            'sourceItemId' => $this->getSourceItemId(),
            'canonicalUrl' => $this->getCanonicalUrl(),
            'bundleId' => $this->getBundleId(),
            'isBundleCover' => $this->isBundleCover(),
            'selfLink' => $this->getSelfLink(),
            'isPinned' => $this->isPinned(),
            'pinScore' => $this->getPinScore(),
            'isDeleted' => $this->isDeleted(),
            'inReplyTo' => $this->getInReplyTo(),
            'title' => $this->getTitle(),
            'text' => $this->getText(),
            'html' => $this->getHtml(),
            'speakableType' => $this->getSpeakableType(),
            'speakableText' => $this->getSpeakableText(),
        );
        
        $notification = $this->getNotification();
        
        if($notification instanceof NotificationConfig)
        {
            $retval['notification'] = $notification->toArray();
        } else {
            $retval['notification'] = null;
        }
        
        $created = $this->getCreated();
        
        if($created instanceof \DateTime) {
            $created = $created->format(\DateTime::RFC3339);
        } else {
            $created = null;
        }
        
        $retval['created'] = $created;
        
        $updated = $this->getUpdated();
        
        if($updated instanceof \DateTime) {
            $updated = $updated->format(\DateTime::RFC3339);
        } else {
            $updated = null;
        }
        
        $retval['updated'] = $updated;
        
        $displayTime = $this->getDisplayTime();
        
        if($displayTime instanceof \DateTime) {
            $displayTime = $displayTime->format(\DateTime::RFC3339);
        } else {
            $displayTime = null;
        }
        
        $retval['displayTime'] = $displayTime;
        
        $location = $this->getLocation();
        
        if($location instanceof Location) {
            $retval['location'] = $location->toArray();
        }
        
        $recipients = $this->getRecipients();
        
        $retval['recipients'] = array();
        foreach($recipients as $recipient) {
            $retval['recipients'][] = $recipient->toArray();
        }
        
        $attachments = $this->getAttachments();
        
        $retval['attachments'] = array();
        
        foreach($attachments as $attachment) {
            $retval['attachments'][] = $attachment->toArray();
        }
        
        $menuItems = $this->getMenuItems();
        
        $retval['menuItems'] = array();
        
        foreach($menuItems as $menuItem) {
            $retval['menuItems'][] = $menuItem->toArray();
        }
        
        return $retval;
    }
    
    public function getAttachment($index = 0)
    {
            $client = $this->getServiceLocator()->get('Phass\Api\Client');
            
            $attachments = $this->getAttachments();
            
            if(empty($attachments) || !isset($attachments[$index])) {
                return null;
            }
            
            try {
                $response = $client->execute("timeline::attachment::get", array('itemId' => $this->getId(), 'attachmentId' => $attachments[$index]->getId()));
            } catch(ApiCallException $e) {
                if($e->getCode() == 404) {
                    return null;
                }
                
                throw $e;
            }
            
            return $response;
    }
    
    public function fromJsonResult(array $result)
    {
        $this->setKind(isset($result['kind']) ? $result['kind'] : null)
             ->setId(isset($result['id']) ? $result['id'] : null)
             ->setSelfLink(isset($result['selfLink']) ? $result['selfLink'] : null)
             ->setCreated(isset($result['created']) ? $this->convertToDateTime($result['created']) : null)
             ->setUpdated(isset($result['updated']) ? $this->convertToDateTime($result['updated']) : null)
             ->setDisplayTime($this->convertToDateTime(isset($result['displayTime']) ? $result['displayTime'] : null))
             ->setETag(isset($result['etag']) ? $result['etag'] : null)
             ->setSourceItemId(isset($result['sourceItemId']) ? $result['sourceItemId'] : null)
             ->setCanonicalUrl(isset($result['canonicalUrl']) ? $result['canonicalUrl'] : null)
             ->setBundleId(isset($result['bundleId']) ? $result['bundleId'] : null)
             ->setBundleCover(isset($result['isBundleCover']) ? (bool)$result['isBundleCover'] : null)
             ->setPinned(isset($result['isPinned']) ? (bool)$result['isPinned'] : null)
             ->setPinScore(isset($result['pinScore']) ? $result['pinScore'] : null)
             ->setDeleted(isset($result['isDeleted']) ? (bool)$result['isDeleted'] : null)
             ->setInReplyTo(isset($result['inReplyTo']) ? $result['inReplyTo'] : null)
             ->setTitle(isset($result['title']) ? $result['title'] : null)
             ->setText(isset($result['text']) ? $result['text'] : null)
             ->setHtml(isset($result['html']) ? $result['html'] : null)
             ->setSpeakableType(isset($result['speakableType']) ? $result['speakableType'] : null)
             ->setSpeakableText(isset($result['speakableText']) ? $result['speakableText'] : null);
             
        if(isset($result['creator'])) {
            $creatorObj = $this->getServiceLocator()->get('Phass\Contact');
            $creatorObj->fromJsonResult($result['creator']);
            $this->setCreator($creatorObj);
        }
        
        if(isset($result['menuItems']) && is_array($result['menuItems'])) {
            $menuItems = array();
            
            foreach($result['menuItems'] as $menuItem) {
                $menuItemObj = $this->getServiceLocator()->get('Phass\Timeline\MenuItem');
                $menuItemObj->fromJsonResult($menuItem);
                
                $menuItems[] = clone $menuItemObj;
            }
            
            $this->setMenuItems($menuItems);
        }
        
        if(isset($result['notification'])) {
            $notificationObj = $this->getServiceLocator()->get('Phass\Timeline\NotificationConfig');
            $notificationObj->fromJsonResult($result['notification']);
            
            $this->setNotification($notificationObj);
        }
        if(isset($result['location'])) {
            $locationObj = $this->getServiceLocator()->get('Phass\Location');
            $locationObj->fromJsonResult($result['location']);
            
            $this->setLocation($locationObj);
        }
        
        if(isset($result['recipients']) && is_array($result['recipients'])) {
            foreach($result['recipients'] as $contact)
            {
                $contactObj = $this->getServiceLocator()->get('Phass\Contact');
                $contactObj->fromJsonResult($contact);
                $this->_recipients[] = clone $contactObj;
            }
        }
        
        if(isset($result['attachments']) && is_array($result['attachments'])) {
            foreach($result['attachments'] as $attachment)
            {
                $attachmentObj = $this->getServiceLocator()->get('Phass\Timeline\Attachment');
                $attachmentObj->fromJsonResult($attachment);
                $this->_attachments[] = clone $attachmentObj;
            }
        }
        
        if(isset($result['menuItems']) && is_array($result['menuItems'])) {
            foreach($result['menuItems'] as $menuItem)
            {
                $menuItemObj = $this->getServiceLocator()->get('Phass\Timeline\MenuItem');
                $menuItemObj->fromJsonResult($menuItem);
                
                $this->_menuItems[] = clone $menuItemObj;
            }
        }
        
        return $this;
    }
    
    public function setDefaultNotification()
    {
        $n = $this->getServiceLocator()->get('Phass\Timeline\NotificationConfig');
        $n->setLevel(NotificationConfig::LEVEL_DEFAULT);
        $this->setNotification($n);
        return $this;
    }
    
    public function insert()
    {
        $glassService = $this->getServiceLocator()->get('Phass\Service\GlassService');
        return $glassService->execute('timeline::insert', $this);
    }
}