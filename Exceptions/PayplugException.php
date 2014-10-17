<?php

namespace Alcalyn\PayplugBundle\Exceptions;

class PayplugException extends \Exception
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, null, null);
    }
}
