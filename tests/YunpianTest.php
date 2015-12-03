<?php

use dcb9\Yunpian\sdk\Yunpian;

class YunpianTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Yunpian
     */
    protected $component;

    public function setUp()
    {
        $this->component = Yii::createObject([
            'class' => Yunpian::className(),
            'apiKey' => 'not_exist_key',
        ]);
    }

    public function testFileTransport()
    {
        $component = $this->component;
        $component->useFileTransport = true;
        $component->fileTransportPath = __DIR__;
        $fileName = '20150907-190215-6418-4977.txt';
        $component->fileTransportCallback = function ($mobile, $text) use ($fileName) {
            return $fileName;
        };

        $this->assertTrue($this->component->sendSms('01234567890', 'test content'));

        $realFile = $component->fileTransportPath . '/' . $fileName;
        $this->assertTrue(file_exists($realFile));
        unlink($realFile);
    }

    public function testLastError()
    {
        $sms = $this->component;
        $sms->useFileTransport = false;
        if (!$sms->sendSms('01234567890', 'test content') && $sms->hasError()) {
            $error = $sms->getLastError();
            $this->assertTrue(isset($error['code']));
            $this->assertTrue(isset($error['msg']));
            $this->assertTrue(isset($error['detail']));
        }
    }

    /**
     * @expectedException yii\base\InvalidConfigException
     */
    public function testInvalidConfigException()
    {
        Yii::createObject([
            'class' => Yunpian::className(),
        ]);
    }
}