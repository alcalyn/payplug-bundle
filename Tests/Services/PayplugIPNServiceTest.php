<?php

namespace Alcalyn\PayplugBundle\Tests\Services;

use Symfony\Component\HttpFoundation\Request;
use Alcalyn\PayplugBundle\Model\IPN;
use Alcalyn\PayplugBundle\Services\PayplugIPNService;
use Alcalyn\PayplugBundle\Tests\Parameters;

class PayplugIPNServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testIPNProcess()
    {
        $ipnService = $this->createIPNService();
        
        // Thanks to payplug php library for their sample signature
        $ipnSignature = "ckHaBITi/jJMbq/pv/SsZv+iSSVs3Vphh92wEyv3Zrjpd9rA/gsRhCdwyYrbi1zn/alnhcnx4O9BsqWt+MmyLf7y4EnzyuM1rUapKuIESYuorxUn2oPivGYRCWXQcIfIC8QCQqy5SxDwyG8Hzp1Eoq+2HfNd3tGRLcPXe8+mXfEWX3Lja65CF5Ut+i9tCOkUNs+FRcpAwjBSd2qCdR0VhF25gVLO0+9vt1gVtL+8Z+ECcvqyT4BEX655V3LZEYhGjfssz5XXKpqynRHSQkt265yKs+W78x+alrm9x6lppTFnRz+e5Z95sTls8JpkVdOR1tfTie0KP9/UaLx53+iD9w==";
        $ipnContent = '{"status": 0, "origin": null, "last_name": "Library", "custom_datas": "29", "customer": "2", "first_name": "PHP", "amount": 4364, "email": "testlib@payplug.fr", "state": "paid", "custom_data": "29", "id_transaction": 201925, "order": "42"}';
        
        $ipnRequest = new Request(array(), array(), array(), array(), array(), array(), $ipnContent);
        $ipnRequest->headers->set('payplug-signature', $ipnSignature);
        
        $valid = $ipnService->verifyIPNRequest($ipnRequest);
        
        $this->assertTrue($valid);
        
        $ipn = $ipnService->createIPNFromBody($ipnContent);
        
        $this->assertEquals(4364, $ipn->getAmount());
        $this->assertEquals('29', $ipn->getCustomData());
        $this->assertEquals('2', $ipn->getCustomer());
        $this->assertEquals('testlib@payplug.fr', $ipn->getEmail());
        $this->assertEquals('PHP', $ipn->getFirstName());
        $this->assertEquals(201925, $ipn->getIdTransaction());
        $this->assertEquals('Library', $ipn->getLastName());
        $this->assertEquals(42, $ipn->getOrder());
        $this->assertNull($ipn->getOrigin());
        $this->assertEquals('paid', $ipn->getState());
    }
    
    public function testIPNValuesBackwardCompatibility()
    {
        $ipnService = $this->createIPNService();
        
        $ipn = $ipnService->createIPNFromBody('{"new_ipn_value": 42}');
        
        $this->assertNull($ipn->getCustomer());
    }
    
    /**
     * @return PayplugIPNService
     */
    private function createIPNService()
    {
        return new PayplugIPNService(Parameters::getPublicKey(), IPN::getClassName());
    }
}
