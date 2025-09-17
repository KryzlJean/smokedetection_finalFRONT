Write-Host "Starting React Native app with optimized memory settings..." -ForegroundColor Green
Write-Host ""

# Set Node.js memory limit to avoid "Data cannot be cloned, out of memory" error
$env:NODE_OPTIONS = "--max-old-space-size=8192"

# Start the app
npm run start

Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
