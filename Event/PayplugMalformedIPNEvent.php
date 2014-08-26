<?php

namespace Alcalyn\PayplugBundle\Event;

use Symfony\Component\HttpFoundation\Request;

class PayplugMalformedIPNEvent extends PayplugEvent
{
    /**
     * Event throw on a malformed IPN received.
     * 
     * The listener receive a PayplugMalformedIPNEvent event
     * 
     * @var string
     */
    const PAYPLUG_IPN_MALFORMED = 'event.payplug.ipn.malformed';
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Return the request responsible of the malformed IPN
     * 
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
