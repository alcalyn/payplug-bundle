<?php

namespace Alcalyn\PayplugBundle\Services;

use Alcalyn\PayplugBundle\Entity\Payment;

class PayplugService
{
    /**
     * Payplug url from account configuration
     * 
     * @var string
     */
    private $baseUrl;
    
    /**
     * Your private key from account configuration
     * 
     * @var string
     */
    private $privateKey;
    
    /**
     * @param string $baseUrl Payplug url from account configuration
     * @param string $privateKey Your private key from account configuration
     */
    public function __construct($baseUrl, $privateKey)
    {
        $this->baseUrl = $baseUrl;
        $this->privateKey = $privateKey;
    }
    
    /**
     * Generate payment url for $payment
     * 
     * @param Payment $payment
     * 
     * @return string
     */
    public function generateUrl(Payment $payment)
    {
        $params = array(
            'amount'        => $payment->getAmount(),
            'currency'      => $payment->getCurrency(),
            'ipn_url'       => $payment->getIpnUrl(),
            'email'         => $payment->getEmail(),
            'first_name'    => $payment->getFirstName(),
            'last_name'     => $payment->getLastName(),
        );
        
        $url_params = http_build_query($params);
        $data = urlencode(base64_encode($url_params));
        
        $signature = null;
        $privatekey = openssl_pkey_get_private($this->privateKey);
        openssl_sign($url_params, $signature, $privatekey, OPENSSL_ALGO_SHA1);
        $signatureBase64 = urlencode(base64_encode($signature));
        
        return $this->baseUrl . '?data=' . $data . '&sign=' . $signatureBase64;
    }
}
