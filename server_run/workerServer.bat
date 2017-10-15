@echo off
TITLE worker
goto begin
if "%1" == "h" goto begin
mshta vbscript:createobject("wscript.shell").run("""%~nx0"" h",0)(window.close)&&exit
:begin

START /b /w workerServer_run.bat
PAUSE
exit