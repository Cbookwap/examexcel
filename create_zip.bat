@echo off
echo ExamExcel Distribution Package Creator
echo =====================================
echo.

REM Get current date for filename
for /f "tokens=2 delims==" %%a in ('wmic OS Get localdatetime /value') do set "dt=%%a"
set "YY=%dt:~2,2%" & set "YYYY=%dt:~0,4%" & set "MM=%dt:~4,2%" & set "DD=%dt:~6,2%"
set "datestamp=%YYYY%-%MM%-%DD%"

set "zipname=ExamExcel-CBT-v1.0-%datestamp%.zip"

echo Creating distribution package: %zipname%
echo.

REM Check if dist folder exists
if not exist "dist" (
    echo Error: dist folder not found!
    echo Please run create_distribution.php first.
    pause
    exit /b 1
)

REM Create zip file using PowerShell (available on Windows 10+)
echo Compressing files...
powershell -command "Compress-Archive -Path 'dist\*' -DestinationPath '%zipname%' -Force"

if exist "%zipname%" (
    echo.
    echo ✓ Distribution package created successfully!
    echo 📦 Package: %zipname%
    echo 📁 Location: %cd%\%zipname%
    echo.
    echo 🚀 Ready for distribution!
    echo.
    echo Next steps:
    echo 1. Test the package on a clean server
    echo 2. Share %zipname% with end users
    echo 3. Provide installation instructions
    echo.
) else (
    echo.
    echo ❌ Failed to create zip package!
    echo Please check if you have sufficient permissions.
    echo.
)

pause
