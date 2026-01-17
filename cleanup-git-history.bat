@echo off
REM Git History Cleanup Script for BangunanPro
REM This script removes .env.docker and .env.docker.backup from git history

echo ========================================
echo BangunanPro - Git History Cleanup
echo ========================================
echo.
echo This will remove sensitive files from git history.
echo IMPORTANT: This will rewrite git history!
echo.

REM Check if BFG is available
if not exist "bfg.jar" (
    echo ERROR: bfg.jar not found in current directory!
    echo.
    echo Please download BFG Repo-Cleaner:
    echo https://rtyley.github.io/bfg-repo-cleaner/
    echo.
    echo Save as 'bfg.jar' in this directory.
    pause
    exit /b 1
)

REM Confirm before proceeding
echo Current directory: %CD%
echo.
set /p CONFIRM="Are you sure you want to proceed? (yes/no): "
if /i not "%CONFIRM%"=="yes" (
    echo Cleanup cancelled.
    pause
    exit /b 0
)

echo.
echo Step 1: Creating backup...
set BACKUP_NAME=BangunanPro-backup-%DATE:/=-%_%TIME::=-%
set BACKUP_NAME=%BACKUP_NAME: =%
cd ..
xcopy BangunanPro "%BACKUP_NAME%\" /E /I /H /Y >nul
cd BangunanPro
echo Backup created: ..\%BACKUP_NAME%
echo.

echo Step 2: Removing .env.docker from history...
java -jar bfg.jar --delete-files .env.docker
if errorlevel 1 goto error
echo.

echo Step 3: Removing .env.docker.backup from history...
java -jar bfg.jar --delete-files .env.docker.backup  
if errorlevel 1 goto error
echo.

echo Step 4: Cleaning up repository...
git reflog expire --expire=now --all
git gc --prune=now --aggressive
if errorlevel 1 goto error
echo.

echo Step 5: Verifying cleanup...
echo Checking for .env.docker in history...
git log --all --oneline -- .env.docker
if errorlevel 1 (
    echo No .env.docker found in history - SUCCESS!
) else (
    echo WARNING: .env.docker still in history
)
echo.

echo ========================================
echo Cleanup Complete!
echo ========================================
echo.
echo NEXT STEPS:
echo 1. Verify everything works: docker-compose up -d
echo 2. If you have a remote repository:
echo    git push origin --force --all
echo    git push origin --force --tags
echo.
echo 3. All collaborators must RE-CLONE the repository
echo.
echo Backup location: ..\%BACKUP_NAME%
echo.
pause
exit /b 0

:error
echo.
echo ERROR: Cleanup failed!
echo Please check the error message above.
echo.
echo Your repository backup is at: ..\%BACKUP_NAME%
pause
exit /b 1
