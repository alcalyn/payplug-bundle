<?php

namespace Alcalyn\PayplugBundle\Model;

/**
 * Transaction
 * 
 * Superclass containing fields which appear both in Payment and IPN classes.
 */
class Transaction
{
    /**
     * REQUIRED
     * 
     * Transaction amount, in cents (such as 4207 for 42,07€).
     * 
     * @var integer
     */
    private $amount;

    /**
     * The customer’s email address, either provided when creating the payment URL
     * or entered manually on the payment page by the customer.
     * 
     * @var string
     */
    private $email;

    /**
     * The customer’s first name, either provided when creating the payment URL
     * or entered manually on the payment page by the customer.
     * 
     * @var string
     */
    private $firstName;

    /**
     * The customer’s last name, either provided when creating the payment URL
     * or entered manually on the payment page by the customer.
     * 
     * @var string
     */
    private $lastName;

    /**
     * Customer ID provided when creating the payment URL.
     * 
     * @var string
     */
    private $customer;

    /**
     * Order ID provided when creating the payment URL.
     * 
     * @var string
     */
    private $order;

    /**
     * Custom data provided when creating the payment URL.
     * 
     * @var string
     */
    private $customData;

    /**
     * Information about your website version (e.g., ‘My Website 1.2 payplug_php0.9 PHP 5.3’),
     * provided when creating the payment URL, with additional data sent by the library itself.
     * 
     * @var string
     */
    private $origin;
    
    /**
     * Set amount
     *
     * @param integer $amount
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
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
