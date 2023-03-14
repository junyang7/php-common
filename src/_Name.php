<?php

namespace Junyang7\PhpCommon;

class _Name
{

    public static function lowCamelCaseToUpperCamelCase($name)
    {

        return preg_replace_callback("/^([a-z])/", function ($matched) {
            return strtoupper($matched[1]);
        }, $name);

    }

    public static function lowCamelCaseToUnderline($name)
    {

        return strtolower(ltrim(preg_replace("/([A-Z])/", "_$1", $name), "_"));

    }

    public static function upperCamelCaseToLowCamelCase($name)
    {

        return preg_replace_callback("/^([A-Z])/", function ($matched) {
            return strtolower($matched[1]);
        }, $name);

    }

    public static function upperCamelCaseToUnderline($name)
    {

        return strtolower(ltrim(preg_replace("/([A-Z])/", "_$1", $name), "_"));

    }

    public static function underlineToLowCamelCase($name)
    {

        return preg_replace_callback("/(_([a-z]))/", function ($matched) {
            return strtoupper($matched[2]);
        }, $name);

    }

    public static function underlineToUpperCamelCase($name)
    {

        return ucfirst(preg_replace_callback("/(_([a-z]))/", function ($matched) {
            return strtoupper($matched[2]);
        }, $name));

    }

}
