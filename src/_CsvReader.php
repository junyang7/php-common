<?php

namespace Junyang7\PhpCommon;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class _CsvReader
{

    private $reader;
    private $sheet;

    public function __construct()
    {

        $this->reader = ReaderEntityFactory::createCSVReader();

    }

    /**
     * 打开
     * @param $file string 路径
     * @return void
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function open($file)
    {

        $this->reader->open($file);

        foreach ($this->reader->getSheetIterator() as $sheet) {
            $this->sheet = $sheet;
            return;
        }

    }

    /**
     * 读取
     * @return \Generator
     */
    public function read()
    {

        foreach ($this->sheet->getRowIterator() as $row) {
            yield $row->toArray();
        }

    }

    /**
     * 关闭
     * @return void
     */
    public function close()
    {

        $this->reader->close();

    }

}
