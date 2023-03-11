<?php

namespace Junyang7\PhpCommon;

class _Sign
{

    /**
     * MD5
     * @param $data_list array 参数
     * @param $key string 秘钥
     * @param $filter_k_list array 键白名单
     * @param $filter_v_list array 值白名单
     * @return string
     */
    public static function md5($data_list, $key, $filter_k_list = ["sign", "sign_type", "access_token",], $filter_v_list = ["",])
    {

        foreach ($data_list as $k => $v) {
            if (in_array($k, $filter_k_list, true) || in_array($v, $filter_v_list, true)) {
                unset($data_list[$k]);
            }
        }

        ksort($data_list);

        $parameter = [];
        foreach ($data_list as $k => $v) {
            $parameter[] = $k . "=" . $v;
        }

        $string = implode("&", $parameter);
        $stringKey = $string . $key;

        return md5($stringKey);

    }

}
