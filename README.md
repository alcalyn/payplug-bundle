AlcalynPayplugBundle
====================

[Payplug](https://www.payplug.fr/) integration for symfony2

## Installation


### Step 1: Download using composer

``` js
{
    "require": {
        "alcalyn/payplug-bundle": "0.x"
    }
}
```

Update your composer.

``` bash
php composer.phar update
```


### Step 2: Register the Bundle

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Alcalyn\PayplugBundle\AlcalynPayplugBundle(),
    );
}
```


### Step 3: Configure the bundle and your account settings

Add these lines to your **config.yml**:

``` yaml
# Payplug configuration
alcalyn_payplug:
    account:
        url:                %payplug_account_url%
        amount_min:         %payplug_account_amount_min%
        amount_max:         %payplug_account_amount_max%
        currencies:         %payplug_account_currencies%
        payplugPublicKey:   %payplug_account_payplugPublicKey%
        yourPrivateKey:     %payplug_account_yourPrivateKey%
```

And theses lines into **parameters.yml**, and optionally into **parameters.yml.dist**:

``` yaml
    payplug_account_url:                ~
    payplug_account_amount_min:         ~
    payplug_account_amount_max:         ~
    payplug_account_currencies:         ~
    payplug_account_payplugPublicKey:   ~
    payplug_account_yourPrivateKey:     ~
```

Then run this command to load your Payplug account settings following
[Payplug documentation](http://payplug-developer-documentation.readthedocs.org/en/latest/#configuration):

``` bash
php app/console payplug:account:update
```

(*Your Payplug email and password will be prompted*)

This command uses curl to load your account parameters from https://www.payplug.fr/portal/ecommerce/autoconfig

If the command fails, go to [the Payplug autoconfig page](https://www.payplug.fr/portal/ecommerce/autoconfig)
and copy/paste your parameters manually.

> **Warning**:
>
> Be sure to never commit your account settings by commiting your **parameters.yml**


## Basic usage:

### Generating payment url:

``` php
use Alcalyn\PayplugBundle\Model\Payment;

// ...

    public function wtfAction()
    {
        // Create a payment of 16 €
        $payment = new Payment(1600, Payment::EUROS);

        // Get Payplug payment service
        $payplugPayment = $this->get('payplug.payment');

        // Generate url
        $payplugPayment->generateUrl($payment); // returns "https://www.payplug.fr/p/aca8...ef?data=...&sign=..."
    }
```


### Treat IPNs

AlcalynPayplugBundle dispatch "**event.payplug.ipn**"
[event](http://symfony.com/doc/current/components/event_dispatcher/introduction.html).<br />
So listen it like that:

Create the listener class:
``` php
// src/Acme/AcmeBundle/EventListener/PaymentListener.php

namespace Acme\AcmeBundle\EventListener;

use Alcalyn\PayplugBundle\Event\PayplugIPNEvent;

class PaymentListener
{
    /**
     * @param PayplugIPNEvent $event
     */
    public function onPayment(PayplugIPNEvent $event)
    {
        $ipn = $event->getIPN();

        // treat $ipn
    }
}
```

Register your listener:
``` yaml
# src/Acme/AcmeBundle/Resources/services.yml
services:
    acme.listeners.payment:
        class: Acme\AcmeBundle\EventListener\PaymentListener
        tags:
            - { name: kernel.event_listener, event: event.payplug.ipn, method: onPayment }
```



## License

This bundle is under the MIT license. See the complete license:

[Resources/meta/LICENSE](https://github.com/alcalyn/payplug-bundle/blob/master/Resources/meta/LICENSE)
