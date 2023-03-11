<?php

namespace Junyang7\PhpCommon;

class _File
{

    /**
     * 文件是否存在
     * @param $path string 文件路径
     * @return bool
     */
    public static function exists($path)
    {

        return is_file($path);

    }

    /**
     * 删除文件
     * 如果操作失败，则会抛出异常
     * @param $path string 文件路径
     * @return void
     * @throws \Exception
     */
    public static function delete($path)
    {

        if (self::exists($path)) {
            if (false === unlink($path)) {
                throw new \Exception("文件删除失败");
            }
        }

    }

    /**
     * 写入文件
     * 如果目录不存在会尝试先创建目录
     * 如果操作失败，则会抛出异常
     * @param $path string 文件路径
     * @param $content string 内容
     * @return void
     * @throws \Exception
     */
    public static function write($path, $content, $flags = FILE_APPEND)
    {

        _Directory::create(dirname($path));

        if (false === file_put_contents($path, $content, $flags)) {
            throw new \Exception("文件写入失败");
        }

    }

    /**
     * 读取文件
     * 如果文件不存在，则抛出异常
     * 如果操作失败，则会=抛出异常
     * @param $path string 文件路径
     * @return string
     * @throws \Exception
     */
    public static function read($path)
    {

        if (!self::exists($path)) {
            throw new \Exception("文件不存在");
        }

        $res = file_get_contents($path);

        if (false === $res) {
            throw new \Exception("文件读取失败");
        }

        return $res;

    }

    /**
     * 获取文件内容类型
     * 如果文件不存在，则抛出异常
     * @param $path string 文件路径
     * @return string
     * @throws \Exception
     */
    public static function getContentType($path)
    {

        if (!self::exists($path)) {
            throw new \Exception("文件不存在");
        }

        switch (pathinfo($path)["extension"]) {
            case "html":
            case "htm":
                return "text/html";
            case "css":
                return "text/css";
            case "js":
                return "text/javascript";
            case "ico":
                return "image/x-icon";
            case "jpe":
                return "image/jpeg";
            case "webp":
                return "image/webp";
            default:
                return "text/plain";
        }

    }

}
