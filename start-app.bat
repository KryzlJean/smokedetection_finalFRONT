@echo off
echo Starting React Native app with optimized memory settings...
echo.

REM Set Node.js memory limit to avoid "Data cannot be cloned, out of memory" error
set NODE_OPTIONS=--max-old-space-size=8192

REM Start the app
npm run start

pause
