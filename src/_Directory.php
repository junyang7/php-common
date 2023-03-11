<?php

namespace Junyang7\PhpCommon;

class _Directory
{

    /**
     * 目录是否存在
     * @param $path string 目录路径
     * @return bool
     */
    public static function exists($path)
    {

        return file_exists($path) && is_dir($path);

    }

    /**
     * 创建目录
     * 如果目录已存在，则忽略
     * 如果目录创建失败，则抛出异常
     * @param $path string 目录路径
     * @return void
     * @throws _Exception
     */
    public static function create($path)
    {

        if (!self::exists($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new _Exception("目录创建失败");
            }
        }

    }

    /**
     * 删除目录
     * 如果操作失败，则抛出异常
     * @param $path string 目录路径
     * @return void
     * @throws _Exception
     */
    public static function delete($path)
    {

        $path = rtrim($path, " \t\n\r\0\x0B/");
        if (is_dir($path)) {
            $dir = @opendir($path);
            if (false === $dir) {
                throw new \Exception("文件夹打开失败");
            }
            while ($item = readdir($dir)) {
                if ($item != "." && $item != "..") {
                    $item_path = $path . "/" . $item;
                    if (is_dir($item_path)) {
                        self::delete($item_path);
                    } else {
                        if (false === unlink($item_path)) {
                            throw new \Exception("删除文件失败");
                        }
                    }
                }
            }
            closedir($dir);
            if (false === rmdir($path)) {
                throw new \Exception("删除目录失败");
            }
        }

    }

    /**
     * 读取目录
     * 如果目录不存在，则抛出异常
     * @param $path string 目录路径
     * @return array
     * @throws \Exception
     */
    public static function read($path)
    {

        if (!self::exists($path)) {
            throw new \Exception("目录不存在");
        }

        $file_list = [];

        foreach (scandir($path) as $file) {
            if ($file == "." || $file == "..") {
                continue;
            }
            $file_list[] = $path . "/" . $file;
        }

        sort($file_list);

        return $file_list;

    }

}
