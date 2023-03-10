<?php

namespace Junyang7\PhpCommon;

class _Datetime
{

    /**
     * 获取时间
     * @param $format string 格式
     * @param $timestamp int 时间戳
     * @return string
     * @throws \Exception
     */
    public static function getByFormatAndTimestamp($format, $timestamp)
    {

        $res = @date($format, $timestamp);

        if (false === $res) {
            throw new \Exception("date格式化操作失败");
        }

        return $res;

    }

    /**
     * 获取时间
     * @param $format string 格式
     * @return string
     * @throws \Exception
     */
    public static function getByFormat($format)
    {

        return self::getByFormatAndTimestamp($format, time());

    }

    /**
     * 获取时间
     * @param $timestamp int 时间戳
     * @return string
     * @throws \Exception
     */
    public static function getByTimestamp($timestamp)
    {

        return self::getByFormatAndTimestamp("Y-m-d H:i:s", $timestamp);

    }

    /**
     * 获取时间
     * @return string
     * @throws \Exception
     */
    public static function get()
    {

        return self::getByFormatAndTimestamp("Y-m-d H:i:s", time());

    }

}
