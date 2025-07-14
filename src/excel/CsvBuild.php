<?php
/**
 * Created by PhpStorm.
 * User: vring
 * Date: 2021/11/23
 * Time: 10:25
 */

namespace vring\excel;


class CsvBuild extends Build
{


    /**
     * 生成表格数据抽象方法
     * @return mixed|string
     */
    protected function build()
    {
        $res = [];
        foreach ($this->tableData as $k=>$item){
            $res[$k] = '"' . join('","', $item) . '"';
        }
        return join("\r\n", $res);
    }

}