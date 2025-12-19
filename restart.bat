@echo off
echo ========================================
echo   Restarting PHP Development Environment
echo ========================================
echo.

echo Stopping containers...
docker-compose down

echo.
echo Starting containers...
docker-compose up -d

echo.
echo ========================================
echo   Environment restarted!
echo ========================================
echo.
echo Services:
echo   - PHP Application: http://localhost:8080
echo   - phpMyAdmin:      http://localhost:8000
echo.
pause

