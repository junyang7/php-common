<?php

namespace Junyang7\PhpCommon;

class _Base64
{

    /**
     * Base64编码
     * @param $data string 明文
     * @return string
     */
    public static function encode($data)
    {

        return rtrim(strtr(base64_encode($data), "+/", "-_"), "=");

    }

    /**
     * Base64解码
     * @param $data string 密文
     * @param $strict bool
     * @return string
     * @throws \Exception
     */
    public static function decode($data, $strict = true)
    {

        $res = base64_decode(strtr($data, "-_", "+/") . str_repeat("=", 3 - (3 + strlen($data)) % 4), $strict);

        if (false === $res) {
            throw new \Exception("base64_decode操作失败");
        }

        return $res;

    }

}
