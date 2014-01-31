<?php

namespace Phass\Html;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Filter\StripTags;

class Filter implements ServiceLocatorAwareInterface, FactoryInterface
{
    use \Phass\Entity\SimpleFactoryTrait;
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    
    protected $allowedTags = array(
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 
        'img', 'li', 'ol', 'ul', 'article', 
        'aside', 'details', 'figure', 'figcaption',
        'footer', 'header', 'nav', 'section', 'summary',
        'time', 'blockquote', 'br', 'div', 'hr', 'p',
        'span', 'b', 'big', 'center', 'em', 'i', 'u',
        's', 'small', 'strike', 'strong', 'style',
        'sub', 'sup', 'table', 'tbody', 'td', 'tfoot',
        'th', 'thead', 'tr'
    );
    
    public function filterHtml($inputHtml)
    {
        $filter = new StripTags();
        $filter->setOptions(array('allowTags' => $this->allowedTags));
        return $filter->filter($inputHtml);
    }
}