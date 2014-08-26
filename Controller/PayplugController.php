<?php

namespace Alcalyn\PayplugBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Alcalyn\PayplugBundle\Event\PayplugIPNEvent;
use Alcalyn\PayplugBundle\Event\PayplugMalformedIPNEvent;

class PayplugController extends Controller
{
    /**
     * Script for Payplug IPN
     * 
     * @Route(
     *      "payplug_ipn",
     *      name = "payplug_ipn",
     *      requirements = {
     *          "_method" = "POST"
     *      }
     * )
     */
    public function ipnAction(Request $request)
    {
        $ipnService = $this->get('payplug.ipn');
        $eventDispatcher = $this->get('event_dispatcher');
        $isSignatureValid = $ipnService->verifyIPNRequest($request);
        
        if ($isSignatureValid) {
            $ipn = $ipnService->createIPNFromBody($request->getContent());
            $event = new PayplugIPNEvent($ipn);
            $eventDispatcher->dispatch(PayplugIPNEvent::PAYPLUG_IPN, $event);
        } else {
            $event = new PayplugMalformedIPNEvent($request);
            $eventDispatcher->dispatch(PayplugMalformedIPNEvent::PAYPLUG_IPN_MALFORMED, $event);
        }
        
        return new Response();
    }
}
