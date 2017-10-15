<?php
use Workerman\Worker;
$WorkermanPath='/../vendor/workerman/workerman'.(strtoupper(substr(PHP_OS,0,3))==='WIN'?'-for-win':'').'/';
require_once __DIR__ .$WorkermanPath.'Autoloader.php';
require_once __DIR__ .$WorkermanPath.'GlobalData/Server.php';
$worker = new GlobalData\Server();
Worker::runAll();
?>