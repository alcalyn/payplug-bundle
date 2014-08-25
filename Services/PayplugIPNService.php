<?php

namespace Alcalyn\PayplugBundle\Services;

use Alcalyn\PayplugBundle\Entity\IPN;

class PayplugIPNService
{
    private $payplugPublicKey;
    
    public function __construct($payplugPublicKey)
    {
        $this->payplugPublicKey = $payplugPublicKey;
    }
    
    public function verifyIPN($body, $signature)
    {
        $publicKey = openssl_pkey_get_public($this->payplugPublicKey);
        return openssl_verify($body, $signature, $publicKey, OPENSSL_ALGO_SHA1);
    }
    
    public function createIPNFromBody($body)
    {
        $data = json_decode($body, true);
        
        return $this->createIPNFromData($data);
    }
    
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
