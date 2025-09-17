# Setup Instructions - Fix Network & Memory Issues

## 🚨 Issues Fixed

1. **Network Request Failed** - App can't reach PHP server
2. **Memory Error** - "Data cannot be cloned, out of memory"

## 🔧 Quick Fix Steps

### Step 1: Configure Your Server IP
Edit `api-config.js` and change the URL to your actual server:

```javascript
export const API_CONFIG = {
  // Change this to your actual server IP address
  BASE_URL: 'http://YOUR_ACTUAL_IP/smokedetection-api',
};
```

**Common IP addresses:**
- **Android Emulator**: `http://10.0.2.2/smokedetection-api` (default)
- **Physical Device**: `http://192.168.1.5/smokedetection-api` (your PC's IP)
- **iOS Simulator**: `http://localhost/smokedetection-api`

### Step 2: Start Your Web Server
1. Start XAMPP/WAMP/Laragon
2. Put `register.php` and `login.php` in `htdocs/smokedetection-api/` folder
3. Ensure Apache is running on port 80

### Step 3: Start the App (Fix Memory Issue)
**Option A: Use Batch File (Windows)**
- Double-click `start-app.bat`

**Option B: Use PowerShell**
- Right-click `start-app.ps1` → "Run with PowerShell"

**Option C: Manual Command**
```bash
# PowerShell
$env:NODE_OPTIONS="--max-old-space-size=8192"; npm run start

# Command Prompt
set NODE_OPTIONS=--max-old-space-size=8192 && npm run start
```

## 🧪 Test Your Setup

1. **Test PHP Endpoint**: Open `http://YOUR_IP/smokedetection-api/register.php` in browser
2. **Test App**: Try to register - check console for the exact URL being called
3. **Check Database**: Verify new user appears in `SmokeDetectiondb.User` table

## 🔍 Troubleshooting

### Still Getting "Network Request Failed"?
1. **Check IP Address**: Ensure `api-config.js` has correct server IP
2. **Same Network**: Phone/emulator and PC must be on same Wi-Fi
3. **Firewall**: Allow inbound HTTP (port 80) on Windows Defender
4. **Server Running**: Verify Apache/PHP is active

### Still Getting Memory Error?
1. **Use Scripts**: Use `start-app.bat` or `start-app.ps1`
2. **Increase Memory**: Try `--max-old-space-size=12288` for 12GB
3. **Clear Cache**: `npm start -- --clear`
4. **Reinstall**: Delete `node_modules` → `npm install`

### Find Your PC's IP Address
```bash
# PowerShell
ipconfig | findstr "IPv4"

# Command Prompt
ipconfig | findstr "IPv4"
```

## 📱 Platform-Specific Notes

- **Android Emulator**: Always use `10.0.2.2` (maps to host machine)
- **Physical Android**: Use your PC's actual LAN IP
- **iOS Simulator**: `localhost` works fine
- **Web Build**: Use actual server IP (not localhost)

## 🎯 Quick Test Commands

```bash
# Test PHP endpoint from PC
curl -X POST http://YOUR_IP/smokedetection-api/register.php -H "Content-Type: application/json" -d "{\"firstname\":\"Test\",\"lastname\":\"User\",\"email\":\"test@test.com\",\"password\":\"test123\",\"phone_number\":\"1234567890\"}"

# Start app with memory fix
$env:NODE_OPTIONS="--max-old-space-size=8192"; npm run start
```

## 📞 Need Help?

1. Check the console logs in the app
2. Verify the exact URL being called
3. Test the PHP endpoint in browser first
4. Ensure database connection works

