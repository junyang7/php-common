<?php

namespace Junyang7\PhpCommon;

class _Aes
{

    /**
     * 编码
     * @param $data string 明文
     * @param $k32 string
     * @param $i16 string
     * @return string
     * @throws \Exception
     */
    public static function encode($data, $k32, $i16)
    {

        $res = openssl_encrypt($data, "AES-256-CBC", $k32, OPENSSL_RAW_DATA, $i16);

        if (false === $res) {
            throw new \Exception("openssl_encrypt操作失败");
        }

        return _Base64::encode($res);

    }

    /**
     * 解码
     * @param $data string 密文
     * @param $k32 string
     * @param $i16 string
     * @return string
     * @throws \Exception
     */
    public static function decode($data, $k32, $i16)
    {

        $res = openssl_decrypt(_Base64::decode($data), "AES-256-CBC", $k32, OPENSSL_RAW_DATA, $i16);

        if (false === $res) {
            throw new \Exception("openssl_decrypt操作失败");
        }

        return $res;

    }

}
