<?php

namespace Alcalyn\PayplugBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Alcalyn\PayplugBundle\Model\IPN;

class PayplugIPNService
{
    /**
     * Payplug public key from configuration
     * 
     * @var string
     */
    private $payplugPublicKey;
    
    /**
     * @param string $payplugPublicKey
     */
    public function __construct($payplugPublicKey)
    {
        $this->payplugPublicKey = $payplugPublicKey;
    }
    
    public function verifyIPNRequest(Request $request)
    {
        $signature = base64_decode($request->headers->get('payplug-signature'));
        $body = $request->getContent();
        
        return $this->verifyIPN($body, $signature);
    }
    
    /**
     * Verify ipn content
     * 
     * @param string $body
     * @param string $signature
     * 
     * @return boolean
     */
    public function verifyIPN($body, $signature)
    {
        $publicKey = openssl_pkey_get_public($this->payplugPublicKey);
        return openssl_verify($body, $signature, $publicKey, OPENSSL_ALGO_SHA1);
    }
    
    /**
     * Create IPN instance from body json data
     * 
     * @param string $body
     * 
     * @return IPN
     */
    public function createIPNFromBody($body)
    {
        $data = json_decode($body, true);
        
        return $this->createIPNFromData($data);
    }
    
    /**
     * Build IPN instance from array
     * 
     * @param object $data
     * 
     * @return IPN
     */
    public function createIPNFromData($data)
    {
        $ipn = new IPN();
        
        return $ipn
            ->setState($data->state)
            ->setIdTransaction($data->id_transaction)
            ->setAmount($data->amount)
            ->setEmail($data->email)
            ->setFirstName($data->first_name)
            ->setLastName($data->last_name)
            ->setCustomer($data->customer)
            ->setOrder($data->order)
            ->setCustomData($data->custom_data)
            ->setOrigin($data->origin)
        ;
    }
}
