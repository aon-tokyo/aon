@echo off
REM Double-click or run from cmd: pushes to GitHub and mirrors to E:\ (override with LOCAL_MIRROR_PATH)
setlocal
cd /d "%~dp0.."
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0sync-github-and-local-mirror.ps1" %*
if errorlevel 1 exit /b 1
