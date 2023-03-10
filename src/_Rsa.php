<?php

namespace Junyang7\PhpCommon;

class _Rsa
{

    /**
     * 编码
     * @param $data string 明文
     * @param $public_key string 公钥
     * @return string
     * @throws \Exception
     */
    public static function encode($data, $public_key)
    {

        if (false === openssl_public_encrypt($data, $encrypted, $public_key)) {
            throw new \Exception("openssl_public_encrypt操作失败");
        }

        return _Base64::encode($encrypted);

    }

    /**
     * 解码
     * @param $data string 密文
     * @param $private_key string 私钥
     * @return string
     * @throws \Exception
     */
    public static function decode($data, $private_key)
    {

        if (false === openssl_private_decrypt(_Base64::decode($data), $decrypted, $private_key)) {
            throw new \Exception("openssl_private_decrypt操作失败");
        }

        return $decrypted;

    }

    /**
     * 生成秘钥对
     * @param $configure array
     * @return array
     * @throws \Exception
     */
    public static function generate($configure = ["digest_alg" => "SHA512", "private_key_bits" => 4096,])
    {

        $key = openssl_pkey_new($configure);

        if (false === $key) {
            throw new \Exception("openssl_pkey_new操作失败");
        }

        if (false === openssl_pkey_export($key, $pri)) {
            throw new \Exception("openssl_pkey_export操作失败");
        }

        $detail = openssl_pkey_get_details($key);

        if (false === $detail) {
            throw new \Exception("openssl_pkey_get_details操作失败");
        }

        return [
            "pub" => $detail["key"],
            "pri" => $pri,
        ];

    }

}
