<?php

namespace App\Http\Helper;

use Exception;

define('AES_METHOD', 'aes-256-cbc');

class DataEncryptor
{
    const PASSWORD = '59dca3e5bd8d10917fbcb03bd12d3ddd';
    const IV = '3db8c34a897c5db6b6eafab42ed077f7';

    public static function encrypt($data)
    {
        if (OPENSSL_VERSION_NUMBER <= 268443727) {
            throw new RuntimeException('OpenSSL Version too old, vulnerability to Heartbleed');
        }
        $iv = hex2bin(self::IV);
        $data = is_array($data) ? json_encode($data) : $data;
        $ciphertext = openssl_encrypt($data, AES_METHOD, self::PASSWORD, OPENSSL_RAW_DATA, $iv);
        return base64_encode($ciphertext);
    }

    public static function decrypt($data)
    {
        try {
            $iv = hex2bin(self::IV);
            $ciphertext = base64_decode($data);
            $decoded = openssl_decrypt($ciphertext, AES_METHOD, self::PASSWORD, OPENSSL_RAW_DATA, $iv);
            if ($decoded !== false) {
                $array = json_decode($decoded, true);
                return !empty($array) ? $array : $decoded;
            }
        } catch (Exception $ex) {
        }
        return null;
    }
}
