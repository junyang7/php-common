<?php

namespace Junyang7\PhpCommon;

class _Date
{

    /**
     * 获取日期
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
     * 获取日期
     * @param $format string 格式
     * @return string
     * @throws \Exception
     */
    public static function getByFormat($format)
    {

        return self::getByFormatAndTimestamp($format, time());

    }

    /**
     * 获取日期
     * @param $timestamp int 时间戳
     * @return string
     * @throws \Exception
     */
    public static function getByTimestamp($timestamp)
    {

        return self::getByFormatAndTimestamp("Y-m-d", $timestamp);

    }

    /**
     * 获取日期
     * @return string
     * @throws \Exception
     */
    public static function get()
    {

        return self::getByFormatAndTimestamp("Y-m-d", time());

    }

}
