<?php
/**
 * Author: vring
 * Date: 2024/4/19
 * Email: <971626354@qq.com>
 */


namespace test;

use PHPUnit\Framework\TestCase;
use vring\validate\AliBankCard;

class AliBankCardTest extends TestCase
{
    public function testbank()
    {
        $alic = new AliBankCard();
        $alic->getBinkType('6214832018989180');
    }
}
