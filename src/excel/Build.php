<?php
/**
 * Created by PhpStorm.
 * User: vring
 * Date: 2023/11/28 0028
 * Time: 9:01
 */

namespace vring\excel;


use vring\util\FileResponse;
use vring\util\FileStore;

abstract class Build
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $map;

    /**
     * 表格头部
     * @var array
     * */
    protected $header = [];

    /**
     * 表格识别数据,纯数据不含表头
     * @var array
     * */
    protected $item = [];

    /**
     * 表格识别数据,含表头
     * @var array
     * */
    protected $tableData = [];

    //csv
    const EXCEL_BUILD_TYPE_CSV = 0;
    //excel
    const EXCEL_BUILD_TYPE_EXCEL = 1;

    /**
     * CsvBuild constructor.
     * @param array $data
     * @param array $map
     */
    public function __construct(array $data, array $map)
    {

        $this->data = $data;
        $this->map = $map;
        $this->parseHeader();
    }

    /**
     * @param array $data
     * @param array $map
     * @param int $type
     * @return Build
     * @throws \Exception
     */
    static public function init(array $data, array $map, int $type = self::EXCEL_BUILD_TYPE_EXCEL):self{
        switch ($type){
            case self::EXCEL_BUILD_TYPE_CSV:
                return new CsvBuild($data,$map);
            case self::EXCEL_BUILD_TYPE_EXCEL:
                return new ExcelBuild($data,$map);
            default:
                throw new \Exception("未知的导出表格类型.");
        }
    }

    /**
     * 生成表格执行方法
     * @return mixed
     */
    abstract protected function build();


    /**
     * 输出表格字节数据
     * @return void
     */
    public function render()
    {
        echo $this->build();
    }

    /**
     * 响应下载文件数据
     * @param $filename
     */
    public function rep($filename)
    {
        $tmpfile = FileStore::tempFile();
        $this->save($tmpfile);
        $response = new FileResponse($tmpfile, $filename);
        $response->responseFileBinary();
    }

    /**
     * 保存文件到指定目录
     * @param $filePath
     * @return bool
     */
    public function save($filePath)
    {
        return (bool)@file_put_contents($filePath, $this->build());
    }

    /**
     * 匹配解析表头信息
     * @return void
     */
    protected function parseHeader()
    {
        $res = [];
        $header = [];
        foreach ($this->data as $k => $v) {
            $csv = [];
            foreach ($this->map as $mk => $mv) {
                foreach ($v as $key => $item) {
                    if (preg_match("/{$mk}/", $key, $match)) {
                        if (empty($header[$k])) {
//                        print_r($key);
                            if (is_callable($mv[0])) {
                                $header[$key] = $mv[0]($item, $match);
                            } else {
                                $header[$key] = $mv[0];
                            }

                        }
                        $call = $mv[1] ?? '';
                        if (is_callable($call)) {
//                                $csv[] = $call($item);
                            $csv[] = $call($item, $match,$v);
                        } else {
                            $csv[] = $item;
                        }
                    }

                }

            }
            $res[$k] = $csv;
        }
        $this->item = $res;
        $this->header = $header;
      /*  print_r($res);
        print_r($header);*/
//        if ($this->item){
            array_unshift($res, array_values($header));
//        }
        $this->tableData = $res;
    }
}