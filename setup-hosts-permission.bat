@echo off
:: ============================================================
:: Multi-Tenant eCommerce - Hosts File Permission Setup
:: ============================================================
:: This script grants write permission on the Windows hosts file
:: so the application can automatically register new tenant
:: subdomains without requiring manual edits.
::
:: MUST be run as Administrator!
:: Right-click > Run as administrator
:: ============================================================

net session >nul 2>&1
if %errorlevel% neq 0 (
    echo ============================================================
    echo  ERROR: This script must be run as Administrator!
    echo  Right-click the file and select "Run as administrator"
    echo ============================================================
    pause
    exit /b 1
)

echo ============================================================
echo  Multi-Tenant eCommerce - Hosts Permission Setup
echo ============================================================
echo.

:: Grant modify permission to Everyone on hosts file
echo [1/3] Granting write permission on hosts file...
icacls "C:\Windows\System32\drivers\etc\hosts" /grant "Everyone:(M)" >nul 2>&1

if %errorlevel% equ 0 (
    echo       OK - Permission granted successfully.
) else (
    echo       FAILED - Could not set permission.
    pause
    exit /b 1
)

:: Add base domain to hosts file if not exists
echo [2/3] Adding ecommerce.test to hosts file...
findstr /C:"ecommerce.test" "C:\Windows\System32\drivers\etc\hosts" >nul 2>&1
if %errorlevel% neq 0 (
    echo.>> "C:\Windows\System32\drivers\etc\hosts"
    echo # Multi-Tenant eCommerce>> "C:\Windows\System32\drivers\etc\hosts"
    echo 127.0.0.1 ecommerce.test>> "C:\Windows\System32\drivers\etc\hosts"
    echo       OK - ecommerce.test added.
) else (
    echo       OK - ecommerce.test already exists.
)

:: Flush DNS cache
echo [3/3] Flushing DNS cache...
ipconfig /flushdns >nul 2>&1
echo       OK - DNS cache flushed.

echo.
echo ============================================================
echo  Setup complete!
echo  - ecommerce.test is now accessible
echo  - New tenant subdomains will be registered automatically
echo ============================================================
echo.
pause
