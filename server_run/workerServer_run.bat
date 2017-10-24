@echo off
TITLE worker服务-请不要关闭
php ..\server\WorkerChannel.php ..\server\WorkerBusiness.php
PAUSE
exit