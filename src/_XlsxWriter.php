<?php

namespace Junyang7\PhpCommon;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class _XlsxWriter
{

    private $writer;
    private $sheet;

    public function __construct()
    {

        $this->writer = WriterEntityFactory::createXLSXWriter();

    }

    /**
     * 打开
     * @param $file string 路径
     * @return void
     * @throws \Box\Spout\Common\Exception\IOException
     */
    public function open($file)
    {

        $this->writer->openToFile($file);

    }

    /**
     * 设置当前操作的sheet
     * @param $index int 索引
     * @return void
     * @throws \Box\Spout\Writer\Exception\SheetNotFoundException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function setCurrentSheetByIndex($index = 0)
    {

        foreach ($this->writer->getSheets() as $sheet) {
            if ($sheet->getIndex() === $index) {
                $this->sheet = $sheet;
                $this->writer->setCurrentSheet($this->sheet);
                return;
            }
        }

    }

    /**
     * 设置当前操作的sheet
     * @param $name string 名称
     * @return void
     * @throws \Box\Spout\Writer\Exception\SheetNotFoundException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function setCurrentSheetByName($name = "Sheet1")
    {

        foreach ($this->writer->getSheets() as $sheet) {
            if ($sheet->getName() === $name) {
                $this->sheet = $sheet;
                $this->writer->setCurrentSheet($this->sheet);
                return;
            }
        }

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

        $this->writer->addRow(WriterEntityFactory::createRowFromArray($row));

    }

    /**
     * 关闭
     * @return void
     */
    public function close()
    {

        $this->writer->close();

    }

}
