<?php

namespace Junyang7\PhpCommon;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class _CsvWriter
{

    private $writer;
    private $browser = false;

    public function __construct()
    {

        $this->writer = WriterEntityFactory::createCSVWriter();

    }

    /**
     * 打开
     * @param $file string 路径
     * @param $browser bool 是否直接输出到浏览器
     * @return void
     * @throws \Box\Spout\Common\Exception\IOException
     */
    public function open($file, $browser = false)
    {

        $this->browser = $browser;
        $this->browser ? $this->writer->openToBrowser(urlencode(str_replace("+", "%20", urlencode($file)))) : $this->writer->openToFile($file);

    }

    /**
     * 写入
     * @param $row array 行数据
     * @return void
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function write($row)
    {

        foreach ($row as &$cell) {
            if (is_numeric($cell) && strlen($cell) > 12) {
                $cell = $cell . "\t";
            }
        }

        $this->writer->addRow(WriterEntityFactory::createRowFromArray($row));

        if ($this->browser) {
            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();
        }

    }

    /**
     * 关闭
     * @return void
     */
    public function close()
    {

        $this->writer->close();

        if ($this->browser) {
            exit();
        }

    }

}
