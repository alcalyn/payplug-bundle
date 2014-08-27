<?php

namespace Alcalyn\PayplugBundle\Exceptions;

class PayplugUndefinedAccountParameterException extends PayplugException
{
    public function __construct($missingParameter)
    {
        parent::__construct(sprintf('Payplug account parameter "%s" is needed but not set.', $missingParameter));
    }
}
