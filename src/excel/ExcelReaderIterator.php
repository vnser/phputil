<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2022/7/15 0015
 * Time: 19:51
 */

namespace vring\excel;


abstract class ExcelReaderIterator implements \Iterator
{
    /**
     * @var ExcelReader
     * */
    protected $excelReader;

    protected $headMap = [];

    protected $readGen;
    /**
     * @var string
     */
    private $dataFile;

    /**
     * ExcelReaderIterator constructor.
     * @param string $dataFile 表格文件路径
     * @throws \Exception
     */
    public function __construct(string $dataFile)
    {

        $this->dataFile = $dataFile;
        $this->excelReader = $this->getReader();
        $this->excelReader->setHeadParse($this->headMap, 1, 60);
        $this->readGen = $this->excelReader->read(true);

    }


    /**
     * @return Reader
     * @throws \Exception
     */
    private function getReader(): Reader
    {
        $fileExt = strtolower(pathinfo($this->dataFile,PATHINFO_EXTENSION));
        switch ($fileExt){
            case 'csv':
                return new CsvReader($this->dataFile);
            case 'xls':
            case 'xlsx':
                return new ExcelReader($this->dataFile);
            default:
                throw new \Exception("不支持的Excel文件类型.");
        }
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->readGen->current();
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->readGen->next();
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->readGen->key();
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->readGen->valid();
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->readGen->rewind();
    }

    protected function getDataAll()
    {
        static $data;
        if (isset($data)) {
            return $data;
        }
        foreach ($this as $v) {
            $data [] = $v;
        }
        return $data;
    }
}