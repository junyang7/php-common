<?php

namespace Junyang7\PhpCommon;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class _XlsxReader
{

    private $reader;
    private $sheet;

    public function __construct()
    {

        $this->reader = ReaderEntityFactory::createXLSXReader();
        $this->reader->setShouldFormatDates(true);

    }

    /**
     * 打开
     * @param $file string 路径
     * @return void
     * @throws \Box\Spout\Common\Exception\IOException
     */
    public function open($file)
    {

        $this->reader->open($file);
        $this->setCurrentSheetByIndex(0);

    }

    /**
     * 设置当前操作的sheet
     * @param $index int 索引
     * @return void
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function setCurrentSheetByIndex($index = 0)
    {

        foreach ($this->reader->getSheetIterator() as $sheet) {
            if ($sheet->getIndex() === $index) {
                $this->sheet = $sheet;
                return;
            }
        }

    }

    /**
     * 设置当前操作的sheet
     * @param $name string 名称
     * @return void
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function setCurrentSheetByName($name = "Sheet1")
    {

        foreach ($this->reader->getSheetIterator() as $sheet) {
            if ($sheet->getName() === $name) {
                $this->sheet = $sheet;
                return;
            }
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
