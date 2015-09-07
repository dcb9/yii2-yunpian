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

## Usage

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
