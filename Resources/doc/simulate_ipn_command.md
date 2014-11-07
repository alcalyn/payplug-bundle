Simulate an IPN from command
============================

When you develop your application on localhost, you can't test your IPN process.

So I created this command to easily simulate an IPN.

``` bash
$> php app/console payplug:simulate:ipn
```

This command will dispatch an IPN event, and reveal the most of errors you could encounter when in prod.

The default simulated IPN has an amount of 16 €, customer number and order number are 0.


### Customize your simulated IPN

You can go further in your IPN process by setting custom IPN arguments.


- Setting a **customer and/or order number** which match to your fixtures:

``` bash
$> php app/console payplug:simulate:ipn --order=69 --customer=42
```


- Change the IPN **amount** (*i.e to 12,50 €*):

``` bash
$> php app/console payplug:simulate:ipn --amount=1250
```


- Simulate a **sandbox** IPN:

``` bash
$> php app/console payplug:simulate:ipn --test
```


- Test your **malformed** IPN process:

``` bash
$> php app/console payplug:simulate:ipn --malformed
```


- Simulate a **refunded** IPN:

``` bash
$> php app/console payplug:simulate:ipn --state=refunded
```


- Test an IPN in **prod environment**:

> This is important to test your prod environment as you could encounter different errors,
> don't forget to **clear prod cache before**.

``` bash
$> php app/console payplug:simulate:ipn --prod
```
