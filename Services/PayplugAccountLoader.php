<?php

namespace Alcalyn\PayplugBundle\Services;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use Alcalyn\PayplugBundle\Exceptions\PayplugException;

class PayplugAccountLoader
{
    /**
     * Url of Payplug account autoconfig
     * 
     * @var string
     */
    const PAYPLUG_AUTOCONFIG_URL = 'https://www.payplug.fr/portal/ecommerce/autoconfig';
    
    /**
     * Url of Payplug test account autoconfig
     * 
     * @var string
     */
    const PAYPLUG_AUTOCONFIG_URL_TEST = 'https://www.payplug.fr/portal/test/ecommerce/autoconfig';
    
    /**
     * Kernel root dir
     * 
     * @var string
     */
    private $rootDir;
    
    /**
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }
    
    /**
     * Load Payplug account parameters and set them into parameters.yml
     * 
     * @param string $mail
     * @param string $pass
     * @param boolean $test
     * 
     * @throws PayplugException on curl or authentication error
     */
    public function loadPayplugParameters($mail, $pass, $test = false)
    {
        $result = $this->curlPayplugRequest($mail, $pass, $test);
        
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
        
        if ($test) {
            $payplugAccount['parameters']['payplug_sandbox_account_url'] = $params->url;
            $payplugAccount['parameters']['payplug_sandbox_account_yourPrivateKey'] = $params->yourPrivateKey;
        } else {
            foreach ($params as $key => $value) {
                $payplugAccount['parameters']['payplug_account_'.$key] = $value;
            }
        }
        
        $this->editParameters($payplugAccount);
    }
    
    /**
     * Make a curl authenticated request to Payplug to get account parameters.
     * Warning: CURLOPT_SSL_VERIFYPEER set to false, so there is not TLS certificate check.
     * 
     * @param string $mail
     * @param string $pass
     * @param boolean $test
     * 
     * @throws PayplugException on curl error
     */
    public function curlPayplugRequest($mail, $pass, $test = false)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $mail.':'.$pass,
            CURLOPT_HTTPHEADER => array('Content-type: application/json'),
            CURLOPT_SSL_VERIFYPEER => false,
        );
        
        $curl = curl_init($this->getAutoconfigUrl($test));
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        
        if (false === $result) {
            throw new PayplugException('Curl error: '.curl_error($curl));
        }
        
        return $result;
    }
    
    /**
     * Add or edit parameters in parameters.yml
     * 
     * @param array $parametersArray
     */
    public function editParameters(array $parametersArray)
    {
        $parametersFile = $this->rootDir.'../config/parameters.yml';
        
        $parser = new Parser();
        $dumper = new Dumper();
        
        $parameters = $parser->parse(file_get_contents($parametersFile));
        $newFileContent = $dumper->dump(array_replace_recursive($parameters, $parametersArray), 2);
        
        file_put_contents($parametersFile, $newFileContent);
    }
    
    /**
     * Return autoconfig for environment
     * 
     * @param boolean $test
     * 
     * @return string
     */
    public function getAutoconfigUrl($test = false)
    {
        if ($test) {
            return self::PAYPLUG_AUTOCONFIG_URL_TEST;
        } else {
            return self::PAYPLUG_AUTOCONFIG_URL;
        }
    }
    
    /**
     * Return a detailled error message from status code
     * 
     * @param int $status
     * 
     * @return string
     */
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
