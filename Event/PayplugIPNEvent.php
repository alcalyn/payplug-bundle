<?php

namespace Alcalyn\PayplugBundle\Event;

use Alcalyn\PayplugBundle\Model\IPN;

class PayplugIPNEvent extends PayplugEvent
{
    /**
     * Event throw on valid IPN received.
     * 
     * The listener receive a PayplugIPNEvent event
     * 
     * @var string
     */
    const PAYPLUG_IPN = 'event.payplug.ipn';
    
    /**
     * Event throw on a mistaken IPN received.
     * 
     * The listener receive a PayplugIPNEvent event
     * 
     * @var string
     */
    const PAYPLUG_IPN_FAILED = 'event.payplug.ipn.failed';
    
    /**
     * @var IPN
     */
    private $ipn;
    
    /**
     * @param IPN $ipn
     */
    public function __construct(IPN $ipn)
    {
        $this->ipn = $ipn;
    }
    
    /**
     * @return IPN
     */
    public function getIPN()
    {
        return $this->ipn;
    }
}
