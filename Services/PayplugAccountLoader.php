<?php

namespace Alcalyn\PayplugBundle\Services;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use Alcalyn\PayplugBundle\Exceptions\PayplugException;

class PayplugAccountLoader
{
    const PAYPLUG_AUTOCONFIG_URL = 'https://www.payplug.fr/portal/ecommerce/autoconfig';
    
    private $rootDir;
    
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }
    
    public function loadPayplugParameters($mail, $pass)
    {
        $result = $this->curlPayplugRequest($mail, $pass);
        
        $params = json_decode($result);
        $status = intval($params->status);
        unset($params->status);
        
        if (200 !== $status) {
            $errorName = $this->errorStatusResolver($status);
            
            throw new PayplugException(
                'Payplug response returned status '.$status.' '.$errorName.'. Unable to continue.'
            );
        }
        
        $payplugAccount = array();
        
        foreach ($params as $key => $value) {
            $payplugAccount['parameters']['payplug_account_'.$key] = $value;
        }
        
        $this->editParameters($payplugAccount);
    }
    
    public function curlPayplugRequest($mail, $pass)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $mail.':'.$pass,
            CURLOPT_HTTPHEADER => array('Content-type: application/json'),
            CURLOPT_SSL_VERIFYPEER => false,
        );
        
        $curl = curl_init(self::PAYPLUG_AUTOCONFIG_URL);
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        
        if (false === $result) {
            throw new PayplugException('Curl error: '.curl_error($curl));
        }
        
        return $result;
    }
    
    public function editParameters($payplugAccount)
    {
        $parametersFile = $this->rootDir.'../config/parameters.yml';
        
        $parser = new Parser();
        $dumper = new Dumper();
        
        $parameters = $parser->parse(file_get_contents($parametersFile));
        $newFileContent = $dumper->dump(array_replace_recursive($parameters, $payplugAccount), 2);
        
        file_put_contents($parametersFile, $newFileContent);
    }
    
    public function errorStatusResolver($status)
    {
        switch ($status) {
            case 200:
                return 'Success';
                
            case 401:
                return 'Authentication error';
            
            default:
                return 'Unexpected status';
        }
    }
}
