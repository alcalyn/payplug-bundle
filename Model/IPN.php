<?php

namespace Alcalyn\PayplugBundle\Model;

/**
 * IPN
 * 
 * IPN instance created when payplug send an IPN to your server.
 */
class IPN extends Transaction
{
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
}
