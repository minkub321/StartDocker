@echo off
echo ========================================
echo   Stopping PHP Development Environment
echo ========================================
echo.

echo Stopping containers...
docker-compose down

echo.
echo ========================================
echo   Environment stopped successfully!
echo ========================================
echo.
echo To start again, run: start.bat
echo.
pause

