<?php

namespace Junyang7\PhpCommon;

class _Interceptor
{

    public static function insure($ok, $error = null, $data = null)
    {

        if ($ok) {
            return;
        }

        if (is_array($error) && 2 === count($error)) {
            list($code, $message) = $error;
        } else {
            $code = -1;
            $message = is_scalar($error) ? $error : "";
        }

        throw new _Exception($message, $data, $code);

    }

}
