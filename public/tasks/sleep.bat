@echo off

:: تأخير 10 ثواني
powershell -Command "Start-Sleep -Seconds 20"

:: تصغير كل النوافذ
powershell -Command "(New-Object -ComObject Shell.Application).MinimizeAll()"

