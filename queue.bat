@echo off
START cmd /k "echo.&echo �жӷ���-������&echo.&php think queue:listen --queue slow --memory 1024 --sleep 1 --delay 1 --tries 10 --timeout 600"
START cmd /k "echo.&echo �жӷ���&echo.&php think queue:work --queue default --daemon --memory 1024 --sleep 1 --delay 1 --tries 10"
#PAUSE