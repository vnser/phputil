<?php
/**
 * Author: vring
 * Date: 2024/4/19
 * Email: <971626354@qq.com>
 */


namespace test;

use PHPUnit\Framework\TestCase;
use vring\excel\ExcelBuild;
use vring\util\WebPath;
use vring\validate\AliBankCard;

class AliBankCardTest extends TestCase
{
    public function testbank()
    {
        $alic = new AliBankCard();
        $res = $alic->getBinkType('6214832018989180');

        print_r($res);
    }

    public function testa()
    {
        $_SERVER['HTTP_HOST'] = 'dyauth.vring.vjike.cn';
        $_SERVER['HTTPS'] = 'on';
        var_dump( WebPath::webPathToPhysical('https://dyauth.vring.vjike.cn/storage/default/20250315/抖音小工具v1.1.106d7d9fc801ab7c995cfe0d8447f403fd469bb224.zip '));
    }

    public function testb()
    {
        ExcelBuild::init([], [
            'order.order_sn' => ['订单编号', 'getOrderOrderSn'],
            'card_no' => ['卡号', 'getCardNo'],
            'password' => ['密码', 'getPassword'],
            'status' => ['状态', 'getStatus'],
            'create_time' => ['创建时间', 'getCreateTime'],
        ], ExcelBuild::EXCEL_BUILD_TYPE_EXCEL);
    }
}
