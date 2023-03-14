<?php

namespace UnitTest\File;

use Junyang7\PhpCommon\_Url;
use UnitTest\Tool\Assert;

class Test_Url
{

    public static function test_get()
    {

        Assert::same("ftp://domain.com", _Url::get("ftp", "domain.com"));
        Assert::same("http://domain.com", _Url::get("http", "domain.com", "80"));
        Assert::same("https://domain.com", _Url::get("https", "domain.com", "443"));
        Assert::same("http://domain.com:10001", _Url::get("http", "domain.com", "10001"));
        Assert::same("https://domain.com:10002", _Url::get("https", "domain.com", "10002"));
        Assert::same("https://domain.com/a/b/c", _Url::get("https", "domain.com", "443", "/a/b/c"));
        Assert::same("https://domain.com/a/b/c?a=a&b=b&c=c", _Url::get("https", "domain.com", "443", "/a/b/c", ["a" => "a", "b" => "b", "c" => "c",]));

    }

}
