Use TEST mode
=============

Payplug provides a sandbox/test mode to create test payments.

## Configuration

The sandbox mode have some differents account parameters (payment url and private key).

### Extra configuration

Add sandbox configuration to your **config.yml**:

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
    # Sandbox parameters
    sandbox:
        account:
            url:                %payplug_sandbox_account_test_url%
            yourPrivateKey:     %payplug_sandbox_account_yourPrivateKey%
```

And these lines into **parameters.yml**, and optionally into **parameters.yml.dist**:

``` yaml
    payplug_account_url:                ~
    payplug_account_amount_min:         ~
    payplug_account_amount_max:         ~
    payplug_account_currencies:         ~
    payplug_account_payplugPublicKey:   ~
    payplug_account_yourPrivateKey:     ~

    # Sandbox parameters
    payplug_sandbox_account_url:            ~
    payplug_sandbox_account_yourPrivateKey: ~
```


### Loading account test parameters

You can load them by running this command:

``` bash
php app/console payplug:account:update --test
```


> **Note**:
>
> **If the command fails**, go to https://www.payplug.fr/portal/test/ecommerce/autoconfig
> and copy/paste your test parameters manually.
>
> Your parameters.yml should looks like this:
> [parameters_with_sandbox.yml.example](https://github.com/alcalyn/payplug-bundle/blob/master/Resources/doc/parameters_with_sandbox.yml.example)



## Test Payplug payment

The process is the same, except that you have to generate the payment url with another service.

To see payments in your Payplug admin, you must go to TEST mode in your account configuration.


### Generating payment url

At this time, you can generate payment urls by using service **payplug.payment.test**:

``` php
use Alcalyn\PayplugBundle\Model\Payment;

// ...

    public function wtfAction()
    {
        // Create a payment of 16 €
        $payment = new Payment(1600, Payment::EUROS);

        // Get Payplug payment service
        $payplugPayment = $this->get('payplug.payment.test'); // Notice that we use another service

        // Generate url
        $payplugPayment->generateUrl($payment); // returns "https://www.payplug.fr/p/vyFD?data=...&sign=..."
    }
```


### Payplug test page

The process is the same, except that you go to a test payment page,
you must fill the form with a dummy credit card:

- Number: **5017 6700 0000 1800**
- Expires: **any future date**
- CVV: **400**

See
[Payplug FAQ about test proccess](http://support.payplug.fr/customer/portal/articles/1701656-comment-tester-le-service-qu-est-ce-que-le-mode-test-)
(french).


### Process IPNs

IPN process is the same, except that the [IPN](https://github.com/alcalyn/payplug-bundle/blob/master/Model/IPN.php)
instance you received in your listener has its **is_sandbox** field on **true**.
