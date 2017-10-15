@echo off
TITLE worker服务-请不要关闭
php ../server/workerChannel.php ../server/workerGlobalData.php  ../server/WorkerBusiness.php
PAUSE
exit