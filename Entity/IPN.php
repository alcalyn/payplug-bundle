<?php

namespace Alcalyn\PayplugBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IPN
 *
 * @ORM\Table(name="payplug_ipn")
 * @ORM\Entity(repositoryClass="Alcalyn\PayplugBundle\Repository\IPNRepository")
 */
class IPN
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=31)
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_transaction", type="integer")
     */
    private $idTransaction;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="customer", type="string", length=255)
     */
    private $customer;

    /**
     * @var string
     *
     * @ORM\Column(name="order_", type="string", length=255)
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_data", type="string", length=255)
     */
    private $customData;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=255)
     */
    private $origin;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

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
     * Set amount
     *
     * @param integer $amount
     * @return IPN
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return IPN
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return IPN
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return IPN
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set customer
     *
     * @param string $customer
     * @return IPN
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return string 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set order
     *
     * @param string $order
     * @return IPN
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return string 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set customData
     *
     * @param string $customData
     * @return IPN
     */
    public function setCustomData($customData)
    {
        $this->customData = $customData;

        return $this;
    }

    /**
     * Get customData
     *
     * @return string 
     */
    public function getCustomData()
    {
        return $this->customData;
    }

    /**
     * Set origin
     *
     * @param string $origin
     * @return IPN
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string 
     */
    public function getOrigin()
    {
        return $this->origin;
    }
}
