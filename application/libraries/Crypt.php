<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

const MODE = 'CRYPT_RSA_ENCRYPTION_PKCS1';

class Crypt
{

    public function __construct()
    {
        //parent::__construct();
        $path = APPPATH . 'classes/phpseclib';
        set_include_path(get_include_path() . PATH_SEPARATOR . $path);
        require_once('Crypt/RSA.php');
    }

    public function create_key($pin)
    {
        $rsa = new Crypt_RSA();
        $rsa->setPassword($pin);
        extract($rsa->createKey()); //[privatekey, publickey, partialkey]
        //$private = $rsa->getPrivateKey();
        //$public = $rsa->getPublicKey();
        return array($privatekey, $publickey);
    }

    public function encrypt($message, $key, $pin = null)
    { // $key may be public or private key, make sure you provide a pin if private
        $rsa = new Crypt_RSA();
        $rsa->loadKey($key);
        if ($pin != null) {
            $rsa->setPassword($pin);
        }
        $rsa->setEncryptionMode(MODE);
        return $rsa->encrypt($message);
    }

    public function decrypt($message, $key, $pin = null)
    { // $key may be public or private key, make sure you provide a pin if private
        $rsa = new Crypt_RSA();
        $rsa->loadKey($key);
        if ($pin != null) {
            $rsa->setPassword($pin);
        }
        $rsa->setEncryptionMode(MODE);
        return $rsa->decrypt($message);
    }
}
