<?php

namespace Junyang7\PhpCommon;

class _Array
{

    /**
     * 对数组分组处理
     * @param $row_list array 待处理数组
     * @param $key string 分组key
     * @param $associative bool 是否整理成关联数组，默认false，默认整理成索引数组
     * @return array
     */
    public static function group($row_list, $key, $associative = false)
    {

        $res = [];
        foreach ($row_list as $row) {
            $associative ? $res[$row[$key]] = $row : $res[$row[$key]][] = $row;
        }
        return $res;

    }

    /**
     * 递归的合并数组
     * @param $a array 数组1
     * @param $b array 数组2（会合并到数组1上）
     * @return array
     */
    public static function merge($a, $b)
    {

        if (!is_array($a) || !is_array($b)) {
            return [];
        }

        foreach ($b as $k => $v) {
            if (!isset($a[$k]) || is_scalar($a[$k]) || is_scalar($v)) {
                $a[$k] = $v;
                continue;
            }
            $a[$k] = self::merge($a[$k], $v);
        }

        return $a;

    }

}
