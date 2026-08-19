@echo off
title SIGERCEP Reverb
cd /d "C:\Users\Admin\Herd\sigercep"
set "PHP=C:\Users\Admin\.config\herd\bin\php84\php.exe"

:loop
"%PHP%" artisan reverb:start
echo [%date% %time%] Reverb berhenti/kegagalan, restart dalam 5 detik...
timeout /t 5 /nobreak >nul
goto loop