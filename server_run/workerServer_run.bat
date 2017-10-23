@echo off
TITLE worker服务-请不要关闭
php D:\www\alencms\server\WorkerChannel.php D:\www\alencms\server\WorkerBusiness.php
PAUSE
exit