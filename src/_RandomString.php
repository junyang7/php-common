<?php

namespace Junyang7\PhpCommon;

class _RandomString
{

    /**
     * 获取随机字符串
     * @param $size int 长度
     * @param $string string 字符表
     * @return string
     */
    public static function get($size = 32, $string = "0123456789QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm~!@#$%^&*()_+[]\;,./{}:<>?|")
    {

        $res = "";
        $index_max = strlen($string) - 1;

        for ($i = 0; $i < $size; $i++) {
            $res .= $string[rand(0, $index_max)];
        }

        return $res;

    }

}
