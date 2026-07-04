@echo off
setlocal EnableExtensions EnableDelayedExpansion
chcp 65001 >nul
title CAI DAT VA CHAY DU AN LARAVEL BANG DOCKER

REM ============================================================
REM  Dat file nay trong thu muc goc cua du an, cung voi:
REM  docker-compose.yml, artisan, .env, package.json
REM
REM  Neu muon mang theo du lieu cu, hay chep them:
REM  - san_bong.sql
REM  - thu muc storage\app\public
REM ============================================================

cd /d "%~dp0"

echo.
echo ============================================================
echo   CAI DAT VA CHAY DU AN FOOTBALL FIELD BOOKING
echo ============================================================
echo Thu muc du an: %CD%
echo.

REM ----- Kiem tra file Docker Compose -----
if not exist "docker-compose.yml" if not exist "docker-compose.yaml" if not exist "compose.yml" if not exist "compose.yaml" (
    echo [LOI] Khong tim thay file docker-compose.yml hoac compose.yml.
    echo Hay dat file BAT trong thu muc goc cua du an.
    goto :FAILED
)

REM ----- Kiem tra Docker -----
where docker >nul 2>&1
if errorlevel 1 (
    echo [LOI] Chua cai Docker Desktop hoac Docker chua co trong PATH.
    echo Hay cai va mo Docker Desktop, sau do chay lai file nay.
    goto :FAILED
)

docker compose version >nul 2>&1
if errorlevel 1 (
    echo [LOI] May nay khong ho tro lenh "docker compose".
    echo Hay cap nhat Docker Desktop.
    goto :FAILED
)

docker info >nul 2>&1
if errorlevel 1 (
    echo [THONG BAO] Docker Desktop chua san sang. Dang thu khoi dong...
    if exist "%ProgramFiles%\Docker\Docker\Docker Desktop.exe" (
        start "" "%ProgramFiles%\Docker\Docker\Docker Desktop.exe"
    )
    call :WAIT_DOCKER
    if errorlevel 1 (
        echo [LOI] Docker Desktop chua khoi dong duoc.
        echo Hay mo Docker Desktop thu cong roi chay lai file BAT.
        goto :FAILED
    )
)

echo [OK] Docker da san sang.

REM ----- Tao .env neu chua co -----
if not exist ".env" (
    if exist ".env.example" (
        copy /Y ".env.example" ".env" >nul
        echo [OK] Da tao .env tu .env.example.
    ) else (
        echo [LOI] Khong tim thay .env hoac .env.example.
        goto :FAILED
    )
)

REM ----- Doc cau hinh database tu .env -----
set "DB_DATABASE="
set "DB_USERNAME="
set "DB_PASSWORD="
set "APP_KEY_VALUE="

for /f "tokens=1,* delims==" %%A in ('findstr /B /C:"DB_DATABASE=" ".env"') do set "DB_DATABASE=%%B"
for /f "tokens=1,* delims==" %%A in ('findstr /B /C:"DB_USERNAME=" ".env"') do set "DB_USERNAME=%%B"
for /f "tokens=1,* delims==" %%A in ('findstr /B /C:"DB_PASSWORD=" ".env"') do set "DB_PASSWORD=%%B"
for /f "tokens=1,* delims==" %%A in ('findstr /B /C:"APP_KEY=" ".env"') do set "APP_KEY_VALUE=%%B"

set "DB_DATABASE=!DB_DATABASE:"=!"
set "DB_USERNAME=!DB_USERNAME:"=!"
set "DB_PASSWORD=!DB_PASSWORD:"=!"
set "APP_KEY_VALUE=!APP_KEY_VALUE:"=!"

if not defined DB_DATABASE set "DB_DATABASE=san_bong"
if not defined DB_USERNAME set "DB_USERNAME=san_bong_user"
if not defined DB_PASSWORD set "DB_PASSWORD=san_bong_password"

REM ----- Build va khoi dong container -----
echo.
echo [1/8] Dang build va khoi dong container...
docker compose up -d --build
if errorlevel 1 goto :FAILED

REM Bao dam container app nhan duoc file .env
docker compose exec -T app mkdir -p /var/www/html >nul 2>&1
docker compose cp ".env" "app:/var/www/html/.env" >nul 2>&1

REM ----- Cai Composer -----
echo.
echo [2/8] Dang cai thu vien PHP...
docker compose exec -T app composer install --no-interaction --prefer-dist
if errorlevel 1 (
    echo [CANH BAO] Khong chay duoc Composer trong container app.
    echo Dang thu bang container Composer tam thoi...
    docker run --rm -v "%CD%:/app" -w /app composer:2 install --no-interaction --prefer-dist
    if errorlevel 1 goto :FAILED
)

REM ----- Tao APP_KEY neu thieu -----
echo.
echo [3/8] Dang kiem tra APP_KEY...
if not defined APP_KEY_VALUE (
    echo APP_KEY dang trong. Dang tao khoa moi...
    docker compose exec -T app php artisan key:generate --force
    if errorlevel 1 goto :FAILED
    docker compose cp "app:/var/www/html/.env" ".env" >nul 2>&1
) else (
    echo [OK] APP_KEY da ton tai, khong tao lai.
)

REM ----- Build Vite bang container Node tam thoi -----
echo.
echo [4/8] Dang build giao dien Vite...
if exist "package.json" (
    if exist "package-lock.json" (
        docker run --rm -v "%CD%:/app" -w /app node:20-alpine sh -lc "npm ci && npm run build"
    ) else (
        docker run --rm -v "%CD%:/app" -w /app node:20-alpine sh -lc "npm install && npm run build"
    )

    if errorlevel 1 (
        echo [CANH BAO] Node Alpine build khong thanh cong. Dang thu lai bang node:20...
        if exist "package-lock.json" (
            docker run --rm -v "%CD%:/app" -w /app node:20 sh -lc "npm ci && npm run build"
        ) else (
            docker run --rm -v "%CD%:/app" -w /app node:20 sh -lc "npm install && npm run build"
        )
        if errorlevel 1 goto :FAILED
    )

    if not exist "public\build\manifest.json" (
        echo [LOI] Khong tao duoc public\build\manifest.json.
        goto :FAILED
    )

    REM Copy build vao container de dung duoc ca khi source code khong bind mount
    docker compose exec -T app mkdir -p /var/www/html/public/build >nul 2>&1
    docker compose cp "public/build/." "app:/var/www/html/public/build" >nul
    if errorlevel 1 goto :FAILED
) else (
    echo [THONG BAO] Khong co package.json, bo qua buoc build Vite.
)

REM ----- Cho MySQL san sang -----
echo.
echo [5/8] Dang cho MySQL san sang...
call :WAIT_DATABASE
if errorlevel 1 (
    echo [LOI] Khong ket noi duoc MySQL trong service db.
    echo Kiem tra DB_HOST, DB_DATABASE, DB_USERNAME va DB_PASSWORD trong .env.
    goto :FAILED
)

REM ----- Import database neu co file SQL -----
echo.
echo [6/8] Dang xu ly database...
if exist "san_bong.sql" (
    echo Tim thay file san_bong.sql.
    choice /C YN /N /M "Ban co muon import du lieu cu vao database? [Y/N]: "
    if errorlevel 2 (
        echo [THONG BAO] Da bo qua import san_bong.sql.
    ) else (
        echo Dang import database !DB_DATABASE!...
        docker compose exec -T db mysql --user="!DB_USERNAME!" --password="!DB_PASSWORD!" "!DB_DATABASE!" < "san_bong.sql"
        if errorlevel 1 (
            echo [LOI] Import san_bong.sql khong thanh cong.
            echo Neu database da co bang, hay chon N lan sau de tranh import trung.
            goto :FAILED
        )
        echo [OK] Da import du lieu.
    )
) else (
    echo [THONG BAO] Khong tim thay san_bong.sql.
    echo Script se chay migration, nhung se khong co du lieu cu.
)

REM Chi bo sung cac migration con thieu, khong xoa du lieu
docker compose exec -T app php artisan migrate --force
if errorlevel 1 goto :FAILED

REM ----- Storage link va quyen thu muc -----
echo.
echo [7/8] Dang tao lien ket anh va xoa cache...
docker compose exec -T app sh -lc "mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache && chmod -R 775 storage bootstrap/cache" >nul 2>&1
docker compose exec -T app php artisan storage:link >nul 2>&1
docker compose exec -T app php artisan optimize:clear
if errorlevel 1 goto :FAILED

REM ----- Khoi dong lai app va hien thi trang thai -----
echo.
echo [8/8] Dang khoi dong lai ung dung...
docker compose restart app >nul
docker compose ps

echo.
echo ============================================================
echo   HOAN TAT
echo ============================================================
echo Mo website theo cong PORT hien trong bang docker compose ps.
echo Thuong la:
echo   http://localhost
echo hoac:
echo   http://localhost:8000
echo.
echo Luu y:
echo - Anh cu phai nam trong storage\app\public.
echo - Khong chay "docker compose down -v" neu muon giu database.
echo.
pause
exit /b 0


:WAIT_DOCKER
for /L %%I in (1,1,40) do (
    docker info >nul 2>&1
    if not errorlevel 1 exit /b 0
    timeout /t 3 /nobreak >nul
)
exit /b 1


:WAIT_DATABASE
for /L %%I in (1,1,40) do (
    docker compose exec -T db mysqladmin ping -h 127.0.0.1 --user="!DB_USERNAME!" --password="!DB_PASSWORD!" --silent >nul 2>&1
    if not errorlevel 1 exit /b 0
    timeout /t 3 /nobreak >nul
)
exit /b 1


:FAILED
echo.
echo ============================================================
echo   QUA TRINH BI DUNG DO CO LOI
echo ============================================================
echo Xem log bang lenh:
echo   docker compose logs --tail=100
echo.
pause
exit /b 1
