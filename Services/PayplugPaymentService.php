<?php

namespace Alcalyn\PayplugBundle\Services;

use Symfony\Component\Routing\Router;
use Alcalyn\PayplugBundle\Model\Payment;
use Alcalyn\PayplugBundle\Exceptions\PayplugUndefinedAccountParameterException;

class PayplugPaymentService
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
     * Payplug url from sandbox account configuration
     * 
     * @var string
     */
    private $testBaseUrl;
    
    /**
     * Your private key from sandbox account configuration
     * 
     * @var string
     */
    private $testPrivateKey;
    
    /**
     * Is sandbox enabled
     * 
     * @var boolean
     */
    private $testEnabled;
    
    /**
     * IPN url
     * 
     * @var string
     */
    private $ipnUrl;
    
    /**
     * @param string $baseUrl
     * @param string $privateKey
     * @param string $testBaseUrl
     * @param string $testPrivateKey
     * @param boolean $testEnabled
     */
    public function __construct($baseUrl, $privateKey, $testBaseUrl, $testPrivateKey, $testEnabled)
    {
        $this->baseUrl = $baseUrl;
        $this->privateKey = $privateKey;
        $this->testBaseUrl = $testBaseUrl;
        $this->testPrivateKey = $testPrivateKey;
        $this->testEnabled = $testEnabled;
    }
    
    /**
     * Generate payment url for $payment
     * 
     * @param Payment $payment
     * @param boolean $sandbox set it to true or false to force test payment or real payment
     * 
     * @return string payment url
     * 
     * @throws PayplugUndefinedAccountParameterException if a parameter is missing
     */
    public function generateUrl(Payment $payment, $sandbox = null)
    {
        if (null === $sandbox ? $this->testEnabled : $sandbox) {
            return $this->generateUrlFrom($payment, $this->testBaseUrl, $this->testPrivateKey, true);
        } else {
            return $this->generateUrlFrom($payment, $this->baseUrl, $this->privateKey, false);
        }
    }
    
    /**
     * Generate payment url for $payment from given parameters
     * 
     * @param Payment $payment
     * @param string $baseUrl
     * @param string $privateKey
     * @param boolean $isTest
     * 
     * @return string payment url
     * 
     * @throws PayplugUndefinedAccountParameterException if a parameter is missing
     */
    private function generateUrlFrom(Payment $payment, $baseUrl, $privateKey, $isTest)
    {
        $testParam = $isTest ? 'sandbox_' : '' ;
        
        if (null === $privateKey) {
            throw new PayplugUndefinedAccountParameterException('payplug_'.$testParam.'account_yourPrivateKey');
        }
        
        if (null === $baseUrl) {
            throw new PayplugUndefinedAccountParameterException('payplug_'.$testParam.'account_url');
        }
        
        // Prepare payment parameters
        $this->affectDefaultIpnUrlToPayment($payment);
        $params = $this->convertPaymentToArray($payment);
        
        // Create data parameter
        $url_params = http_build_query($params);
        $data = urlencode(base64_encode($url_params));
        
        // Create signature parameter
        $signature = null;
        $privatekey = openssl_pkey_get_private($privateKey);
        openssl_sign($url_params, $signature, $privatekey, OPENSSL_ALGO_SHA1);
        $signatureBase64 = urlencode(base64_encode($signature));
        
        return $baseUrl . '?data=' . $data . '&sign=' . $signatureBase64;
    }
    
    /**
     * Return default ipn url used by the bundle (Something like "http://yoursite.com/payplug_ipn").
     * 
     * @return string
     */
    public function getIpnUrl()
    {
        return $this->ipnUrl;
    }
    
    /**
     * Set default ipn url used by the bundle (Something like "http://yoursite.com/payplug_ipn").
     * 
     * @param string $ipnUrl
     * 
     * @return PayplugPaymentService
     */
    public function setIpnUrl($ipnUrl)
    {
        $this->ipnUrl = $ipnUrl;
        
        return $this;
    }
    
    /**
     * Set default ipn url used by the bundle
     * based on the ipn route payplug_ipn.
     * 
     * @param Router $router
     * 
     * @return PayplugPaymentService
     */
    public function setIpnUrlFromRouter(Router $router)
    {
        return $this->setIpnUrl($router->generate('payplug_ipn', array(), true));
    }
    
    /**
     * Set automatically ipn url to the default used by this bundle if not set by user
     * 
     * @param Payment $payment
     * 
     * @return PayplugPaymentService
     */
    private function affectDefaultIpnUrlToPayment(Payment $payment)
    {
        if (null === $payment->getIpnUrl()) {
            $payment->setIpnUrl($this->getIpnUrl());
        }
        
        return $this;
    }
    
    /**
     * @param Payment $payment
     * 
     * @return array
     */
    private function convertPaymentToArray(Payment $payment)
    {
        return array(
            'amount'        => $payment->getAmount(),
            'currency'      => $payment->getCurrency(),
            'ipn_url'       => $payment->getIpnUrl(),
            'return_url'    => $payment->getReturnUrl(),
            'cancel_url'    => $payment->getCancelUrl(),
            'email'         => $payment->getEmail(),
            'first_name'    => $payment->getFirstName(),
            'last_name'     => $payment->getLastName(),
            'customer'      => $payment->getCustomer(),
            'order'         => $payment->getOrder(),
            'custom_data'   => $payment->getCustomData(),
            'origin'        => $payment->getOrigin(),
        );
    }
}
