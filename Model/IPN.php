<?php

namespace Alcalyn\PayplugBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * IPN
 * 
 * IPN instance created when payplug send an IPN to your server.
 * 
 * @method IPN setAmount(integer $amount)
 * @method IPN setEmail($email)
 * @method IPN setFirstName($firstName)
 * @method IPN setLastName($lastName)
 * @method IPN setCustomer($customer)
 * @method IPN setOrder($order)
 * @method IPN setCustomData($customData)
 * @method IPN setOrigin($origin)
 */
class IPN extends Transaction
{
    /**
     * Value of field $state when payment is done
     * 
     * @var string
     */
    const PAYMENT_PAID = 'paid';
    
    /**
     * Value of field $state when payment has been refunded
     * 
     * @var string
     */
    const PAYMENT_REFUNDED = 'refounded';
    
    /**
     * The new state of the transaction: 'paid' or 'refunded'.
     * 
     * @var string
     */
    private $state;

    /**
     * The PayPlug transaction ID.
     * We recommend you save it and associate it with this order in your database.
     * 
     * @var integer
     */
    private $idTransaction;

    /**
     * Set state
     *
     * @param string $state
     * @return IPN
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set idTransaction
     *
     * @param integer $idTransaction
     * @return IPN
     */
    public function setIdTransaction($idTransaction)
    {
        $this->idTransaction = $idTransaction;

        return $this;
    }

    /**
     * Get idTransaction
     *
     * @return integer 
     */
    public function getIdTransaction()
    {
        return $this->idTransaction;
    }
    
    /**
     * @return string
     */
    public static function getClassName()
    {
        return __CLASS__;
    }
}
