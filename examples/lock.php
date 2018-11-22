<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/10
 * Time: 下午11:37
 */
require dirname(__DIR__) . '/../../autoload.php';

$lk = \PhpComp\Lock\Lock::make([
    'driver' => 'file', // sem file
    'tmpDir' => __DIR__
]);

printf("Create Lock, driver: %s \n", $lk->getDriver());

$ret = $lk->lock('test');

echo "locked\n";
var_dump($ret);

$ret = $lk->unlock('test');
var_dump($ret);

echo "unlocked\n";
