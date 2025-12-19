@echo off
chcp 65001 >nul
echo ========================================
echo   PHP Development Environment Starter
echo ========================================
echo.

echo [1/4] Checking Docker...
docker --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker is not installed or not running!
    echo Please install Docker Desktop and make sure it's running.
    pause
    exit /b 1
)
echo [OK] Docker is ready
echo.

echo [2/4] Building and starting containers...
echo This may take a few minutes on first run...
docker-compose up -d --build
if errorlevel 1 (
    echo [ERROR] Failed to start containers!
    pause
    exit /b 1
)
echo.

echo [3/4] Waiting for services to be ready...
echo Please wait, services are starting...
timeout /t 10 /nobreak >nul

echo.
echo [4/4] Checking services status...
docker-compose ps

echo.
echo ========================================
echo   Environment is ready!
echo ========================================
echo.
echo Services:
echo   - PHP Application: http://localhost:8080
echo   - Environment Check: http://localhost:8080/check.php
echo   - phpMyAdmin:      http://localhost:8000
echo   - MySQL:           localhost:3306
echo   - Redis:           localhost:6379
echo.
echo Database Credentials:
echo   - Root Password: rootpassword
echo   - Database:      myapp
echo   - Username:      devuser
echo   - Password:      devpassword
echo.
echo phpMyAdmin Login:
echo   - Server: db
echo   - Username: root
echo   - Password: rootpassword
echo.
echo Quick Test Files:
echo   - http://localhost:8080/check.php (Environment Check)
echo   - http://localhost:8080/test-db.php (Database Test)
echo   - http://localhost:8080/test-redis.php (Redis Test)
echo.
echo To stop the environment, run: stop.bat
echo ========================================
echo.
echo Opening browser...
start http://localhost:8080/check.php
pause

