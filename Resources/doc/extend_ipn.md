Extend IPN Class
================

When Payplug make an IPN to your server, this bundle create an instance of IPN and dispatch it
through the event dispatcher.

By default, an instance of [IPN](https://github.com/alcalyn/payplug-bundle/blob/master/Model/IPN.php)
is created with the minimum field provided by Payplug
([see Payplug reference](http://payplug-developer-documentation.readthedocs.org/en/latest/#reference)).

But you could want to add more field, for example the created date, or relations between other entities
before to persist the IPN instance in your database.


## Create your own IPN entity

To do that, just create your IPN class which extends Payplug IPN class:

``` php
<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Alcalyn\PayplugBundle\Model\IPN as BaseIPN;

/**
 * IPN
 *
 * @ORM\Table(name="acme_ipn")
 * @ORM\Entity
 */
class IPN extends BaseIPN
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
```

Then add your columns.


## Configurate PayplugBundle to use your IPN class

Then you have to add to your **config.yml**:

``` yml
# Payplug configuration
alcalyn_payplug:
    # [...]
    class:
        ipn: Acme\DemoBundle\Entity\IPN
```
