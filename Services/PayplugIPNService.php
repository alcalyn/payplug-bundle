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
     * IPN class from configuration used to create ipn instance
     * 
     * @var string
     */
    private $ipnClass;
    
    /**
     * @param string $payplugPublicKey
     */
    public function __construct($payplugPublicKey, $ipnClass)
    {
        $this->payplugPublicKey = $payplugPublicKey;
        $this->ipnClass = $ipnClass;
    }
    
    /**
     * Verify ipn request validity
     * 
     * @param Request $request
     * 
     * @return boolean
     */
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
        return 1 === openssl_verify($body, $signature, $publicKey, OPENSSL_ALGO_SHA1);
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
     * @param array $data
     * 
     * @return IPN
     */
    public function createIPNFromData(array $data)
    {
        $ipn = new $this->ipnClass();
        $data = $this->provideDefaultIPNValues($data);
        
        return $ipn
            ->setState($data['state'])
            ->setIdTransaction($data['id_transaction'])
            ->setAmount($data['amount'])
            ->setEmail($data['email'])
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setCustomer($data['customer'])
            ->setOrder($data['order'])
            ->setCustomData($data['custom_data'])
            ->setOrigin($data['origin'])
            ->setIsTest($data['is_test'])
        ;
    }
    
    /**
     * Provide default values to ensure backward compatibility
     * (i.e when Payplug change or remove a field, avoid blockant exception)
     * 
     * @param array $data
     * 
     * @return array
     */
    private function provideDefaultIPNValues(array $data)
    {
        $defaults = array(
            'status' => null,
            'origin' => null,
            'last_name' => null,
            'custom_datas' => null,
            'customer' => null,
            'first_name' => null,
            'amount' => null,
            'email' => null,
            'state' => null,
            'custom_data' => null,
            'id_transaction' => null,
            'order' => null,
            'is_test' => null,
        );
        
        return array_merge($defaults, $data);
    }
}
