<?php

use dcb9\Yunpian\sdk\helpers\ConstDocHelper;

class ConstDockHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * 测试常量1
     */
    const TEST_CONSTANT_1 = 1;

    /**
     * 测试常量2
     */
    const TEST_CONSTANT_2 = 2;

    /**
     * @var \dcb9\Yunpian\sdk\helpers\ConstDocHelper
     */
    protected $constDocHelper;

    public function setUp()
    {
        $this->constDocHelper = new ConstDocHelper(__CLASS__);
    }

    public function testGetDocComment()
    {
        $this->assertEquals('测试常量1', $this->constDocHelper->getDocComment('TEST_CONSTANT_1'));
        $this->assertEquals('测试常量2', $this->constDocHelper->getDocComment('TEST_CONSTANT_2'));
    }
}