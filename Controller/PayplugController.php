<?php

namespace Alcalyn\PayplugBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Alcalyn\PayplugBundle\Event\PayplugIPNEvent;

class PayplugController extends Controller
{
    /**
     * Script for Payplug IPN
     * 
     * @Route(
     *      "payplug/ipn",
     *      requirements = {
     *          "_method" = "POST"
     *      }
     * )
     */
    public function ipnAction(Request $request)
    {
        $signature = base64_decode($request->headers->get('payplug-signature'));
        $body = $request->getContent();
        
        $ipnService = $this->get('payplug.ipn');
        $isSignatureValid = $ipnService->verifyIPN($body, $signature);
        
        $eventDispatcher = $this->get('event_dispatcher');
        
        if ($isSignatureValid) {
            $ipn = $ipnService->createIPNFromBody($body);
            $event = new PayplugIPNEvent($ipn);
            $eventDispatcher->dispatch(PayplugIPNEvent::PAYPLUG_IPN, $event);
        } else {
            $event = new PayplugIPNEvent(null);
            $eventDispatcher->dispatch(PayplugIPNEvent::PAYPLUG_IPN_FAILED, $event);
        }
        
        return new Response();
    }
}
