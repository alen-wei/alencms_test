<?php
use Workerman\Worker;
require_once __DIR__ .'/../vendor/autoload.php';
require_once __DIR__ .'/../vendor/workerman/channel/src/Server.php';
$worker = new Channel\Server();
Worker::runAll();