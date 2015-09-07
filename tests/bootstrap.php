<?php

if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once(dirname(__DIR__) . '/vendor/autoload.php');
} elseif (file_exists('../../../autoload.php')) {
    // after composer installed autoload file path.
    require_once('../../../autoload.php');
}

if (file_exists(dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php')) {
    require_once(dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php');
} elseif (file_exists('../../../yiisoft/yii2/Yii.php')) {
    // after composer installed autoload file path.
    require_once('../../../yiisoft/yii2/Yii.php');
}
