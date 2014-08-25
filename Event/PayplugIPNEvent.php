<?php

namespace Alcalyn\PayplugBundle\Event;

use Alcalyn\PayplugBundle\Entity\IPN;

class PayplugIPNEvent extends PayplugEvent
{
    /**
     * @var string
     */
    const PAYPLUG_IPN = 'event.payplug.ipn';
    
    /**
     * @var string
     */
    const PAYPLUG_IPN_FAILED = 'event.payplug.ipn.failed';
    
    /**
     * @var IPN
     */
    private $ipn;
    
    /**
     * @param \Alcalyn\PayplugBundle\Entity\IPN $ipn
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
