<?php

namespace Junyang7\PhpCommon;

class _Json
{

    /**
     * JSON编码
     * @param $data array|object 参数
     * @param $flags int
     * @param $depth int
     * @return string
     * @throws \Exception
     */
    public static function encode($data, $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES, $depth = 512)
    {

        $res = json_encode($data, $flags, $depth);

        if (false === $res) {
            throw new \Exception("json_encode操作失败");
        }

        return $res;

    }

    /**
     * JSON解码
     * @param $data string 参数
     * @param $associative bool
     * @param $depth int
     * @param $flags int
     * @return array
     * @throws \Exception
     */
    public static function decode($data, $associative = true, $depth = 512, $flags = JSON_BIGINT_AS_STRING)
    {

        $res = json_decode($data, $associative, $depth, $flags);

        if (is_null($res)) {
            throw new \Exception("json_decode操作失败");
        }

        return $res;

    }

}
