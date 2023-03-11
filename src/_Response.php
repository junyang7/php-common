<?php

namespace Junyang7\PhpCommon;

class _Response implements \JsonSerializable
{

    const OK = "HTTP/1.1 200 OK";
    const FORBIDDEN = "HTTP/1.1 403 Forbidden";
    const MOVE = "HTTP/1.1 301 Moved Permanently";
    const UNAUTHORIZED = "HTTP/1.1 401 Unauthorized";
    const NOT_FOUND = "HTTP/1.1 404 Not Found";
    const METHOD_NOT_ALLOWED = "HTTP/1.1 405 Method Not Allowed";
    const REQUEST_TIMEOUT = "HTTP/1.1 408 Request Time-out";
    const INTERNAL_SERVER_ERROR = "HTTP/1.1 500 Internal Server Error";

    public $code = 0;
    public $message = "";
    public $data = null;
    public $time = 0;
    public $consume = 0;

    public function jsonSerialize()
    {

        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        return [
            "code" => $this->code,
            "message" => $this->message,
            "data" => $this->data,
            "time" => $this->time,
            "consume" => $this->consume,
        ];

    }

}
