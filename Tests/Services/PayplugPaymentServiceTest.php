<?php

namespace Alcalyn\PayplugBundle\Tests\Services;

use Alcalyn\PayplugBundle\Model\Payment;
use Alcalyn\PayplugBundle\Services\PayplugPaymentService;
use Alcalyn\PayplugBundle\Tests\Parameters;

class PayplugPaymentServiceTest extends \PHPUnit_Framework_TestCase
{
    private function createPayplugPaymentService($sandbox)
    {
        $baseUrl = Parameters::URL;
        $privateKey = Parameters::getPrivateKey();
        $defaultIpnUrl = 'http://my-website.com/payplug_ipn';
        
        $baseUrlTest = Parameters::TEST_URL;
        $privateKeyTest = Parameters::getTestPrivateKey();

        $paymentService = new PayplugPaymentService(
                $baseUrl,
                $privateKey,
                $baseUrlTest,
                $privateKeyTest,
                $sandbox
        );
        
        $paymentService->setIpnUrl($defaultIpnUrl);
        
        return $paymentService;
    }
    
    private function createPayment()
    {
        return new Payment(2500, Payment::EUROS);
    }
    
    private function getExpectedPaymentUrl($sandbox)
    {
        if ($sandbox) {
            return 'https://www.payplug.fr/p/baseurltest?data=YW1vdW50PTI1MDAmY3VycmVuY3k9RVVSJmlwbl91cmw9aHR0cCUzQSUyRiUyRm15LXdlYnNpdGUuY29tJTJGcGF5cGx1Z19pcG4%3D&sign=qRRDv7YzVcKwzSjGD776hjtJYymiZPB2VHqZqg9rdzLXO4tm6koZyhBHHp9ZBPGt1rEDW8rBfKLVs%2B0Iq8VuGnxfh61so9SWcor3dXi3wO3NXSBNyER1Pme%2FmqJ4huHefiiW%2Fnjw84KR5l44GbU1hCuT7y4zWz95ys2DLExpHoYvGOIEOhxU3yrmiC6C9jVxhVbKQiLTeutlyOIdEuUjh0r37NDlXGm8%2Fx1v%2Bf8xi3v5SfxB9Qfcd1Q53F0U3QXOPZHj%2FTxSnRDmkqbKDGDZqX%2BErABCDcNITAqsoKQj91Oyci3ypqPeeQJRIxWZawkrymc6hlUhaRkzILhihlvFRg%3D%3D';
        } else {
            return 'https://www.payplug.fr/p/baseurl?data=YW1vdW50PTI1MDAmY3VycmVuY3k9RVVSJmlwbl91cmw9aHR0cCUzQSUyRiUyRm15LXdlYnNpdGUuY29tJTJGcGF5cGx1Z19pcG4%3D&sign=txFaMoIDcn7wRyDnzmw5fL5rymdrTBe4Rb7oRr2h202FuhFiO8EVPIgxbVVc5fHYanCj6t2dgE5v30aLJscq4IuNW5rCuYkU2lqVuaPVPthWCzCcwVuDCbcQFh2WgRdT5yTTcXRJFacnr0F%2Bv2mriF6nf11j6ZqvVfgC%2FJXgPRt5vLNzo5m8u7vSyaeTy%2FuL2988KUVm7zZYd0tyB580n1au4afiIDC8XZe1Wse8IbsEZ3NNDNrTjNGnTFxFfE5jczhh%2FQTqJ6qM2asu1qCm62Lxi43Ho3ZFqFstZOp436hIpdiJJ4LPwpJOo3IzPGFCgVewusBRPwyHwek8deQ9Kw%3D%3D';
        }
    }

    /**
     * Test affectDefaultIpnUrlToPayment
     */
    public function testAffectDefaultIpnUrlToPaymentAffectsGoodIpnUrl()
    {
        $payment = $this->createPayment();
        $paymentService = $this->createPayplugPaymentService(false);
        
        $defaultIpnUrl = 'http://my-website.com/payplug_ipn';
        $paymentService->setIpnUrl($defaultIpnUrl);
        
        $method = new \ReflectionMethod(
            '\Alcalyn\PayplugBundle\Services\PayplugPaymentService', 'affectDefaultIpnUrlToPayment'
        );
        
        $method->setAccessible(true);
        $method->invoke($paymentService, $payment);
        
        $this->assertEquals($payment->getIpnUrl(), $defaultIpnUrl);
    }
    
    /**
     * Test whether generate url works as expected.
     * 
     * Fails if private key is wrong
     * Fails if default ipn url is not set to payment before processing
     */
    public function testGenerateUrlReturnsExpectedUrls()
    {
        $paymentService = $this->createPayplugPaymentService(false);
        $payment = $this->createPayment();
        
        // Test whether payment service return expected payment url
        $paymentUrl = $paymentService->generateUrl($payment);
        $this->assertEquals($paymentUrl, $this->getExpectedPaymentUrl(false));
    }
    
    /**
     * Test whether generate sandbox url works as expected.
     * 
     * Fails if private key is wrong
     * Fails if default ipn url is not set to payment before processing
     */
    public function testGenerateSandboxUrlReturnsExpectedUrls()
    {
        $paymentService = $this->createPayplugPaymentService(true);
        $payment = $this->createPayment();
        
        // Test whether sandbox payment service return expected payment url
        $paymentUrl = $paymentService->generateUrl($payment);
        $this->assertEquals($paymentUrl, $this->getExpectedPaymentUrl(true));
    }
    
    public function testGenerateUrlForceEnvironmentParameter()
    {
        $paymentService = $this->createPayplugPaymentService(false);
        $paymentServiceTest = $this->createPayplugPaymentService(true);
        $payment = $this->createPayment();
        
        // Test whether forcing test url works
        $paymentUrlTest = $paymentService->generateUrl($payment, true);
        $this->assertEquals($paymentUrlTest, $this->getExpectedPaymentUrl(true));
        
        // Test whether forcing real url from a test payment service works
        $paymentUrl = $paymentServiceTest->generateUrl($payment, false);
        $this->assertEquals($paymentUrl, $this->getExpectedPaymentUrl(false));
    }
}
