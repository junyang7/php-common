<?php

namespace Junyang7\PhpCommon;

class _Url
{

    public static function get($protocol, $domain, $port = "", $uri = "", $parameter = [])
    {

        $res = $protocol . "://" . $domain;

        if (80 == $port && "http" != $protocol || 443 == $port && "https" != $protocol) {
            $res .= ":" . $port;
        }

        if (!empty($uri)) {
            $res .= "/" . trim($uri, "/");
        }

        if (!empty($parameter)) {
            $res .= "?";
            $index = 0;
            foreach ($parameter as $k => $v) {
                if ($index > 0) {
                    $res .= "&";
                }
                $res .= $k . "=" . $v;
                $index++;
            }
        }

        return $res;

    }

}
