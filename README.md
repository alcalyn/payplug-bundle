AlcalynPayplugBundle
====================

[Payplug](https://www.payplug.fr/) integration to symfony2.

[![Latest Stable Version](https://poser.pugx.org/alcalyn/payplug-bundle/v/stable.svg)](https://packagist.org/packages/alcalyn/payplug-bundle)
<!-- [![Total Downloads](https://poser.pugx.org/alcalyn/payplug-bundle/downloads.svg)](https://packagist.org/packages/alcalyn/payplug-bundle) -->
[![Latest Unstable Version](https://poser.pugx.org/alcalyn/payplug-bundle/v/unstable.svg)](https://packagist.org/packages/alcalyn/payplug-bundle)
[![License](https://poser.pugx.org/alcalyn/payplug-bundle/license.svg)](https://packagist.org/packages/alcalyn/payplug-bundle)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alcalyn/payplug-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alcalyn/payplug-bundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5db1af60-63e4-40a6-bb5a-671725d9ac73/mini.png)](https://insight.sensiolabs.com/projects/5db1af60-63e4-40a6-bb5a-671725d9ac73)

## Installation


### Step 1: Download using composer

``` js
{
    "require": {
        "alcalyn/payplug-bundle": "1.x"
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


### Step 3: Import Payplug IPN route

To create payments and process IPNs, the bundle need to have its routes enabled.

Add this to **app/config/routing.yml**:

``` yml
# Payplug routing
alcalyn_payplug:
    resource: "@AlcalynPayplugBundle/Resources/config/routing.yml"
    prefix:   /
```


### Step 4: Configure the bundle and your account settings

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

Then run this command to load your Payplug account settings:

``` bash
php app/console payplug:account:update
```

(*Your Payplug email and password will be prompted*)


> **Warning**:
>
> Be sure to never commit your account settings by commiting your **parameters.yml**

See [Payplug documentation](http://payplug-developer-documentation.readthedocs.org/en/latest/#configuration)
for more informations about account configuration.

> **Note**:
>
> This command uses curl to load your account parameters from https://www.payplug.fr/portal/ecommerce/autoconfig
>
> **If the command fails**, go to https://www.payplug.fr/portal/ecommerce/autoconfig
> and copy/paste your parameters manually.
>
> Your parameters.yml should looks like this:
> [parameters.yml.example](https://github.com/alcalyn/payplug-bundle/blob/master/Resources/doc/parameters.yml.example)


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

AlcalynPayplugBundle dispatches
[PayplugIPNEvent](https://github.com/alcalyn/payplug-bundle/blob/master/Event/PayplugIPNEvent.php)
when an IPN is received.

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


## Advanced usage:

 - [Extend IPN class](https://github.com/alcalyn/payplug-bundle/blob/master/Resources/doc/extend_ipn.md)
 - [Listen to malformed IPN](https://github.com/alcalyn/payplug-bundle/blob/master/Resources/doc/malformed_ipn.md)


## License

This bundle is under the MIT license. See the complete license:

[Resources/meta/LICENSE](https://github.com/alcalyn/payplug-bundle/blob/master/Resources/meta/LICENSE)
