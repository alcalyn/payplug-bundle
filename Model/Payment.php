<?php

namespace Alcalyn\PayplugBundle\Model;

/**
 * Payment
 * 
 * Payment instance that you create
 * before redirect your customer to payplug payment page.
 * 
 * @method Payment setAmount(integer $amount)
 * @method Payment setEmail($email)
 * @method Payment setFirstName($firstName)
 * @method Payment setLastName($lastName)
 * @method Payment setCustomer($customer)
 * @method Payment setOrder($order)
 * @method Payment setCustomData($customData)
 * @method Payment setOrigin($origin)
 */
class Payment extends Transaction
{
    /**
     * Currency euro
     * 
     * @var string
     */
    const EUROS = 'EUR';
    
    /**
     * REQUIRED
     * 
     * Transaction currency. Only 'EUR' is allowed at the moment.
     * 
     * @var string
     */
    private $currency;
    
    /**
     * REQUIRED
     * 
     * URL pointing to the ipn.php page, to which PayPlug will send payment and refund notifications.
     * This URL must be accessible from anywhere on the Internet (usually not the case in localhost environments).
     * 
     * @var string
     */
    private $ipnUrl;

    /**
     * URL pointing to your payment confirmation page,
     * to which PayPlug will redirect your customer after the payment.
     * 
     * @var string
     */
    private $returnUrl;

    /**
     * URL pointing to your payment cancelation page,
     * to which PayPlug will redirect your customer if he cancels the payment.
     * 
     * @var string
     */
    private $cancelUrl;
    
    /**
     * @param integer Transaction amount, in cents (such as 4207 for 42,07€).
     * @param string $currency Transaction currency
     */
    public function __construct($amount = 0, $currency = self::EUROS)
    {
        $this
            ->setAmount($amount)
            ->setCurrency($currency)
        ;
    }
    
    /**
     * Set currency
     *
     * @param string $currency
     * @return Payment
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set ipnUrl
     *
     * @param string $ipnUrl
     * @return Payment
     */
    public function setIpnUrl($ipnUrl)
    {
        $this->ipnUrl = $ipnUrl;

        return $this;
    }

    /**
     * Get ipnUrl
     *
     * @return string 
     */
    public function getIpnUrl()
    {
        return $this->ipnUrl;
    }

    /**
     * Set returnUrl
     *
     * @param string $returnUrl
     * @return Payment
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    /**
     * Get returnUrl
     *
     * @return string 
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * Set cancelUrl
     *
     * @param string $cancelUrl
     * @return Payment
     */
    public function setCancelUrl($cancelUrl)
    {
        $this->cancelUrl = $cancelUrl;

        return $this;
    }

    /**
     * Get cancelUrl
     *
     * @return string 
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }
}
