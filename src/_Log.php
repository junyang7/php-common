<?php

namespace Junyang7\PhpCommon;

class _Log
{

    public static $path = "";

    public static function shutdown(...$message)
    {

        self::write("shutdown", $message);

    }

    public static function error(...$message)
    {

        self::write("error", $message);

    }

    public static function exception(...$message)
    {

        self::write("exception", $message);

    }

    public static function request(...$message)
    {

        self::write("request", $message);

    }

    public static function response(...$message)
    {

        self::write("response", $message);

    }

    public static function debug(...$message)
    {

        self::write("debug", $message);

    }

    public static function warn(...$message)
    {

        self::write("warn", $message);

    }

    public static function custom($name, ...$message)
    {

        self::write($name, $message);

    }

    public static function write($name, $message)
    {

        if (!empty(self::$path)) {
            $path = self::$path;
        } else if (defined("LOG_PATH") && !empty(LOG_PATH)) {
            $path = self::$path;
        } else {
            return;
        }

        $row = [];
        $row["guid"] = _Uuid::get();
        $row["content"] = $message;
        $content = _Datetime::get() . "\t" . _Json::encode($row) . "\n";

        $filename = $path . "/" . $name . "." . _Date::getByFormat("Ymd");
        _File::write($filename, $content);

    }

}
