<?php
/**
 * Created by PhpStorm.
 * User: vring
 * Date: 2022/7/15 0015
 * Time: 19:21
 */

namespace vring\excel;
//require_once LIBRARY_PATH . '/phpexcel/PHPExcel.php';
//require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Reader/Excel5.php';
//require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Reader/Excel2007.php';
//require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Writer/Excel5.php';
//require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Writer/Excel2007.php';
//require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Writer/Excel5.php';

use PHPExcel_Cell_DataType;
use PHPExcel_Reader_Excel2007;
use PHPExcel_Reader_Excel5;

class ExcelReader extends Reader
{

    protected $isUtf8 = true;

    /**
     * @var \PHPExcel
     * */
    protected $excel;

    /**
     * @return \PHPExcel
     */
    public function getExcel(): \PHPExcel
    {
        return $this->excel;
    }

    /**
     * @param \PHPExcel $excel
     */
    public function setExcel(\PHPExcel $excel): void
    {
        $this->excel = $excel;
    }

    /**
     * 抽象方法，打开文件，要求子类实现
     * @param string $filePath
     * @return void
     * @throws \Exception
     */
    protected function openFile(string $filePath): void
    {
        $ext = strtolower(pathinfo($filePath,PATHINFO_EXTENSION));
        if (!file_exists($filePath)){
            throw new \Exception('数据文件不存在.');
        }
        $phpExcelReader = null;
        switch ($ext){
            case 'xls':
                $phpExcelReader = new PHPExcel_Reader_Excel5();
                break;
            case 'xlsx':
                $phpExcelReader = new PHPExcel_Reader_Excel2007();
                break;
            default:
                throw new \Exception("不支持的数据类型.");
        }

        $this->excel = $phpExcelReader->load($filePath);

    }

    /**
     * 要求子类加载数据
     * @return void
     * @throws \Exception
     */
    protected function loadData()
    {
        $sheet = null;
        if ($this->sheetName){
            $sheet = $this->excel->getSheetByName($this->sheetName);
//            $this->data = $this->excel->getSheetByName($this->sheetName)->toArray(null,false);
        }else{
            $sheet =  $this->excel->getSheet();
            /*$this->data =  $this->excel->getSheet()->toArray(null,false);
            print_r($this->data);*/
        }
        $this->data = [];
        $sheetIterator = $sheet->getRowIterator();
        foreach ($sheetIterator as $rk=>$item){
            foreach ($item->getCellIterator() as $ck=>$cell){
                if($cell->getDataType() === PHPExcel_Cell_DataType::TYPE_FORMULA){
                    throw new \Exception("检测到表格中有公式，请去掉公式再导入！");
                }
//                /*$this->data[$rk][] =*/ $cell->getValue();
            }

        }

        $this->data = $sheet->toArray(null,false);
//        print_r($this->data);

    }


}