<?php

namespace Alcalyn\PayplugBundle\Model;

/**
 * Payment
 * 
 * Payment instance that you create
 * before redirect your customer to payplug payment page.
 */
class Payment extends Transaction
{
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
