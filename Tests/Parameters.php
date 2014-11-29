<?php

namespace Alcalyn\PayplugBundle\Tests;

class Parameters
{
    const URL = 'https://www.payplug.fr/p/baseurl';
    const TEST_URL = 'https://www.payplug.fr/p/baseurltest';
    
    public static function getPublicKey()
    {
        return "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtN4dpK368PEEYKeee7S5\n1m2a8GUFLDAZ/HgRI1H6diYt87gzDPftn1UyW96YuIBed0T0dtl0tuABaIgGeddR\nuo3zfMpkyYWM2D5UHUEMKzEY5WIyaaWoVYJaZU5DWzCiroKcnUJgKm41RL32/CHU\nSFoymxjOOzpvkazbaY+Ql2GYev2QwKAf7lkH91Wp3frjQYXEFIwYnt6ZET8wPUwX\nMdF0hRaZYlaDQrCB2S/+k4Djb8mXqVkJ0qqgItycL05zyysJw/IGMr2zZ5hQSnfN\nCJ+i33ywnoT/qctGgLW4bGuGdTdcbA7VzdxhXtHaAQjuJvrf+twNCQSLCMbZ6pnK\nzQIDAQAB\n-----END PUBLIC KEY-----\n";
    }
    
    public static function getPrivateKey()
    {
        return file_get_contents(__DIR__.'/SSLTestKeys/private.key');
    }
    
    public static function getTestPublicKey()
    {
        return file_get_contents(__DIR__.'/SSLTestKeys/public_test.key');
    }
    
    public static function getTestPrivateKey()
    {
        return file_get_contents(__DIR__.'/SSLTestKeys/private_test.key');
    }
}
