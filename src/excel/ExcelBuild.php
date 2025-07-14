<?php
/**
 * Created by PhpStorm.
 * User: vring
 * Date: 2023/11/28 0028
 * Time: 9:01
 */

namespace vring\excel;

use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Worksheet;
use PHPExcel_Writer_Excel2007;

require_once LIBRARY_PATH . '/phpexcel/PHPExcel.php';
require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Reader/Excel5.php';
require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Reader/Excel2007.php';
require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Writer/Excel5.php';
require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Writer/Excel2007.php';
require_once LIBRARY_PATH . '/phpexcel/PHPExcel/Writer/Excel5.php';

class ExcelBuild extends Build
{

    /**
     * 生成表格数据抽象方法
     * @return mixed
     * @throws \Exception
     */
    protected function build()
    {
        $file = tempnam(sys_get_temp_dir(), 'vring_excel_build');
        $excel = new PHPExcel();
        $excel->removeSheetByIndex(0);
        $sheet = new PHPExcel_Worksheet($excel, 'Sheet1-数据导出@vring');
//        var_dump($this->tableData);
        $sheet->fromArray($this->tableData);
        $excel->addSheet($sheet);
        $this->setSheetStyle($sheet);
        $excelWriter = new PHPExcel_Writer_Excel2007($excel);
        $excelWriter->save($file);
        return file_get_contents($file);
    }


    /**
     * 设置表格样式
     * @param PHPExcel_Worksheet $sheet
     * @throws \Exception
     */
    protected function setSheetStyle(PHPExcel_Worksheet $sheet)
    {
        foreach ($this->tableData as $row => $item) {

            foreach ($item as $col => $v) {
                $style = $sheet->getStyleByColumnAndRow($col, $row + 1);
                if (0 === $row) {
                    $style->applyFromArray([
                        'font' => array(
                            'name' => '黑体',
                            'bold' => true,
                        ),
                    ]);
                }

                $sheet->getColumnDimensionByColumn($col)->setWidth(16);
                $sheet->getRowDimension($row+1)->setRowHeight(20);
//                $style->
                $style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
                $style->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '000000'
                            ]
                        ],
                        'top' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '000000'
                            ]
                        ],
                        'left' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '000000'
                            ]
                        ],

                        'right' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '000000'
                            ]
                        ]
                    ],


                ]);
            }

        }
    }
}