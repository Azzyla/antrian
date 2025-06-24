@echo off
cd /d "%~dp0\audio"
for %%f in ("nomor antrian *.mp3") do (
    set "oldname=%%~nxf"
    setlocal EnableDelayedExpansion
    set "newname=!oldname: =_!"
    ren "%%f" "!newname!"
    endlocal
)
pause
