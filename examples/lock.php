<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/10
 * Time: 下午11:37
 */
require dirname(__DIR__) . '/../../autoload.php';


$lk = \inhere\lock\Lock::make([
    'driver' => 'sem'
]);

printf("Create SHM, driver: %s \n", $lk->getDriver());

$ret = $lk->lock('test');

echo "locked\n";
var_dump($ret);

$ret = $lk->unlock('test');
var_dump($ret);

echo "unlocked\n";
