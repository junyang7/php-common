<?php

namespace Junyang7\PhpCommon;

class _NanoId
{

    /**
     * 生成唯一随机字符
     * @param $size int 长度
     * @param $alphabet string 字符表
     * @return string
     * @throws \Exception
     */
    public static function get($size = 32, $alphabet = "_-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")
    {

        $len = strlen($alphabet);
        $mask = (2 << (int)(log($len - 1) / M_LN2)) - 1;
        $step = (int)ceil(1.6 * $mask * $size / $len);
        $id = "";

        while (true) {
            $byte_list = unpack("C*", \random_bytes($step));
            foreach ($byte_list as $byte) {
                $byte &= $mask;
                $id .= $alphabet[$byte];
                if (strlen($id) === $size) {
                    return $id;
                }
            }
        }

    }

}
