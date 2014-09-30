Process malformed IPN
=====================

PayplugBundle check IPNs RSA signatures before inform you through event dispatcher
that you received an IPN.

This security procedure prevent fake IPN sent from someone else than Payplug.

If you want to listen malformed IPN for development or test purposes, know that
PayplugBundle dispatches a
[PayplugMalformedIPNEvent](https://github.com/alcalyn/payplug-bundle/blob/master/Event/PayplugMalformedIPNEvent.php)
that you can listen.

This event contains the whole request so you can track every request information.

An example to log malformed IPNs:

``` php
<?php

namespace Acme\DemoBundle\EventListener;

use Symfony\Bridge\Monolog\Logger;
use Alcalyn\PayplugBundle\Event\PayplugMalformedIPNEvent;

class MalformedIPNListener
{
    /**
     * @var Logger
     */
    private $logger;
    
    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * @param PayplugIPNEvent $event
     */
    public function onMalformedIPN(PayplugMalformedIPNEvent $event)
    {
        $request = $event->getRequest(); // Get the request instance
        
        $this->logger->addAlert('Malformed IPN: '.$event->getRequest()->getContent());
    }
}
```

And register it like that:

``` yml
services:
    acme_demo.listeners.malformed_ipn:
        class: Acme\DemoBundle\EventListener\MalformedIPNListener
        arguments: [@logger]
        tags:
            - { name: kernel.event_listener, event: event.payplug.ipn.malformed, method: onMalformedIPN }
```
