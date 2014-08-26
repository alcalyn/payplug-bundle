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

Add these lines to your app/config/config.yml:

``` yml
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

And in your parameters.yml.dist:

``` yml
    payplug_account_url:                ~
    payplug_account_amount_min:         ~
    payplug_account_amount_max:         ~
    payplug_account_currencies:         ~
    payplug_account_payplugPublicKey:   ~
    payplug_account_yourPrivateKey:     ~
```

And paste your account settings in your parameters.yml:

``` yml
    payplug_account_url: https://www.payplug.fr/p/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    payplug_account_amount_min: 1
    payplug_account_amount_max: 5000
    payplug_account_currencies: [ EUR ]

    payplug_account_payplugPublicKey: |
        -----BEGIN PUBLIC KEY-----
        MIIBIjANBdkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtN4dpK368PEEYKeee7S5
        [ ... ]
        zQIDAQAB
        -----END PUBLIC KEY-----

    payplug_account_yourPrivateKey: |
        -----BEGIN RSA PRIVATE KEY-----
        MIIEowIBAAKCAQEAnw1BPxsN18XyhsIdFpE/lMoWepZpv3RY8W+mhVo0tDk+ayBs
        [ ... ]
        [ ... ]
        [ ... ]
        ccNd8YfoF1lBwj9itP+PBCwXvAlAdDmzlySdDCc7UpSgnD798m4m
        -----END RSA PRIVATE KEY-----
```

> **Warning**:
>
> Be sure to never commit your account settings by commiting your parameters.yml

Then get your account settings from this page
(following [Payplug documentation](http://payplug-developer-documentation.readthedocs.org/en/latest/#configuration)):
https://www.payplug.fr/portal/ecommerce/autoconfig
(Your Payplug email and password will be prompted)


## License

This bundle is under the MIT license. See the complete license:

[Resources/meta/LICENSE](https://github.com/alcalyn/payplug-bundle/blob/master/Resources/meta/LICENSE)
