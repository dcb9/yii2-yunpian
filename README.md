Yunpian-sdk for YII2
====================

云片还未出 PHP 的 SDK 支持，只提供了接口，现阶段正在使用 YII2 做开发，所以就把相应的组件共享出来，给需要的人用。

[![Build Status](https://travis-ci.org/dcb9/yii2-yunpian.svg?branch=master)](https://travis-ci.org/dcb9/yii2-yunpian)[![Latest Stable Version](https://poser.pugx.org/dcb9/yii2-yunpian/v/stable.svg)](https://packagist.org/packages/dcb9/yii2-yunpian) [![Total Downloads](https://poser.pugx.org/dcb9/yii2-yunpian/downloads.svg)](https://packagist.org/packages/dcb9/yii2-yunpian) [![Latest Unstable Version](https://poser.pugx.org/dcb9/yii2-yunpian/v/unstable.svg)](https://packagist.org/packages/dcb9/yii2-yunpian) [![License](https://poser.pugx.org/dcb9/yii2-yunpian/license.svg)](https://packagist.org/packages/dcb9/yii2-yunpian)

## Install 

add `dcb9/yii2-yunpian` to composer.json

```
$ composer update 
```

OR

```
$ composer require dcb9/yii2-yunpian
```

## Configurtion

```php
\# file app/config/main.php
<?php

return [
    'components' => [
	   'yunpian' => [
            'class' => 'dcb9\Yunpian\sdk\Yunpian',
            'apiKey' => 'your yunpian apiKey',
            // 'useFileTransport' => false, // 如果该值为 true 则不会真正的发送短信，而是把内容写到文件里面，测试环境经常需要用到！
        ],
    ],
];
```

## Usage

```php
$phone = '01234567890';
// $phone = ['01234567890'];   # 可以为数组
// $phone = '12345678900,01234567890';  # 还可以号码与号码之间用空格隔开
$text ='sms content';
$sms = Yii::$app->yunpian;
if($sms->sendSms($phone, $text))
{
    $responseBody = $sms->getBody();
    # ["code"=>0, "msg"=>"OK", "result" => ["count" => 1, "fee" => 1, "sid" => 3995844410]]
} elseif ($sms->hasError()) {
    $error = $sms->getLastError()
    # ["code" => 2, "msg" => "请求参数格式错误", "detail" => "参数 text 格式不正确，text短信内容头部需要加签名,如【云片网】"]
}
```
