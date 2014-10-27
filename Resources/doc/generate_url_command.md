Generate payment Url from command
=================================

You can generate quickly payment url from command to test your configuration.

``` bash
$> php app/console payplug:generate:url --help

Usage:
 payplug:generate:url [amount] [currency] [-t|--test] [--code] [-i|--interactive] [-f|--firstname="..."] [-l|--lastname="..."] [--email="..."] [--ipn-url="..."] [--return-url="..."] [--cancel-url="..."] [--customer="..."] [--order="..."] [--custom-data="..."] [--origin="..."]

Arguments:
 amount                Amount of the payment in cents (default: 1600)
 currency              Currency of the payment (default: "EUR")

Options:
 --test (-t)           Generate a test payment
 --interactive (-i)    Prompt every missed payment parameters
 --firstname (-f)      First name of the customer
 --lastname (-l)       Last name of the customer
 --email               Email of the customer
 --code                Display code to generate this payment
```

## Examples

 - Generate a payment of 25 €

``` bash
$> php app/console payplug:generate:url 2500
https://www.payplug.fr/p/ac6781671e16871a6871f610b23c5612?data=YW2htrh16v4rth86rth4g35h4dfg14dgh1dgh4gh3mw9aHR0cCUzQSUyR
iUyRmxvY2FsaG9zdCUyRnBheXBsdWdfaXBu&sign=FqGPHJH5jkM5yJj9oUMVAMBwanZOfk3ahKrPyC5XK0kWjjpo04QoV6PnM0lKHT4r7gYNcV4HhiEOVlh
69HnPrD88kAez4Gn38yOOp4J2Kgk8KXovokgtQqvg%3D%3D
```


 - Generate a payment of 25 € and fill customer name and email

``` bash
$> php app/console payplug:generate:url 2500 --firstname=Tyler --lastname=Durden --email=tylen.durden@free.fr
https://www.payplug.fr/p/ac6781671e16871a6871f610b23c5612?data=YW2htrh16v4rth86rth4g35h4dfg14dgh1dgh4gh3mw9aHR0cCUzQSUyR
iUyRmxvY2FsaG9zdCUyRnBheXBsdWdfaXBu&sign=FqGPHJH5jkM5yJj9oUMVAMBwanZOfk3ahKrPyC5XK0kWjjpo04QoV6PnM0lKHT4r7gYNcV4HhiEOVlh
69HnPrD88kAez4Gn38yOOp4J2Kgk8KXovokgtQqvg%3D%3D
```


> **Tip**:
>
> Copy command output to your **clipboard** from command by using:
>
> Linux: `php app/console payplug:generate:url 2500 | xclip` (you need xclip)<br />
> Windows: `php app/console payplug:generate:url 2500 | clip`


 - Generate a sandbox payment of 25 €

``` bash
$> php app/console payplug:generate:url 2500 --test
https://www.payplug.fr/p/ac6781671e16871a6871f610b23c5612?data=YW2htrh16v4rth86rth4g35h4dfg14dgh1dgh4gh3mw9aHR0cCUzQSUyR
iUyRmxvY2FsaG9zdCUyRnBheXBsdWdfaXBu&sign=FqGPHJH5jkM5yJj9oUMVAMBwanZOfk3ahKrPyC5XK0kWjjpo04QoV6PnM0lKHT4r7gYNcV4HhiEOVlh
69HnPrD88kAez4Gn38yOOp4J2Kgk8KXovokgtQqvg%3D%3D
```


 - Use the interactive mode

``` bash
$> php app/console payplug:generate:url --interactive

   Amount in cents: 2500
    Currency [EUR]:
         Firstname: Tyler
          Lastname: Durden
             Email: tyler.durden@free.fr
IPN url [http://localhost/payplug_ipn]:
        Return url:
        Cancel url:
          Customer:
             Order:
       Custom data: Your custom data
            Origin: Command line

https://www.payplug.fr/p/ac6781671e16871a6871f610b23c5612?data=YW2htrh16v4rth86rth4g35h4dfg14dgh1dgh4gh3mw9aHR0cCUzQSUyR
iUyRmxvY2FsaG9zdCUyRnBheXBsdWdfaXBu&sign=FqGPHJH5jkM5yJj9oUMVAMBwanZOfk3ahKrPyC5XK0kWjjpo04QoV6PnM0lKHT4r7gYNcV4HhiEOVlh
69HnPrD88kAez4Gn38yOOp4J2Kgk8KXovokgtQqvg%3D%3D
```


 - Dump the php code to generate the payment

``` bash
$> php app/console payplug:generate:url --interactive --code

   Amount in cents: 2500
    Currency [EUR]:
         Firstname: Tyler
          Lastname: Durden
             Email: tyler.durden@free.fr
IPN url [http://localhost/payplug_ipn]:
        Return url:
        Cancel url:
          Customer:
             Order:
       Custom data: Your custom data
            Origin: Command line

    $payment = new Payment();

    $payment
        ->setAmount(2500)
        ->setCurrency('EUR')
        ->setFirstName('Tyler')
        ->setLastName('Durden')
        ->setEmail('tyler.durden@free.fr')
        ->setCustomData('Your custom data')
        ->setOrigin('Command line')
    ;

    // Get Payplug payment service
    $payplugPayment = $this->get('payplug.payment');

    // Generate url
    $paymentUrl = $payplugPayment->generateUrl($payment);
```
