<?php

namespace Junyang7\PhpCommon;

class _String
{

    /**
     * 字符串是否以xxx开头
     * @param $string string 字符串
     * @param $prefix string xxx
     * @return bool
     */
    public static function hasPrefix($string, $prefix)
    {

        return substr($string, 0, strlen($prefix)) == $prefix;

    }

    /**
     * 字符串是否以xxx结尾
     * @param $string string 字符串
     * @param $suffix string xxx
     * @return bool
     */
    public static function hasSuffix($string, $suffix)
    {

        return substr($string, -strlen($suffix)) == $suffix;

    }

}
