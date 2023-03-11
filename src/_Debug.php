<?php

namespace Junyang7\PhpCommon;

class _Debug
{

    /**
     * 输出
     * 非基本数据类型会被编码成JSON格式
     * @param $data mixed 数据
     * @return void
     * @throws \Exception
     */
    public static function echo($data)
    {

        if (!is_scalar($data)) {
            $data = _Json::encode($data);
        }

        echo _Datetime::get() . "\t" . $data . PHP_EOL;

    }

}
