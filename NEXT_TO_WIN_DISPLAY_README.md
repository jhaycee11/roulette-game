# Next to Win Display - Cross-Network Implementation

This implementation provides an easy way to display the "next-to-win" names from the admin panel on different computers or IPs across the network.

## Features

- **Real-time Display**: Shows the current next-to-win list with auto-refresh
- **Cross-Network Access**: Works from any computer on the same network
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Auto-refresh**: Updates every 30 seconds automatically
- **Offline Handling**: Shows connection status and handles network issues
- **Standalone Option**: Can be used as a standalone HTML file

## Implementation Options

### Option 1: Laravel Route (Recommended)
Access the display page through the Laravel application:

**URL**: `http://your-server-ip:8000/next-to-win-display`

**Features**:
- Integrated with Laravel application
- Uses Laravel's routing and templating
- Better security and error handling

### Option 2: Standalone HTML File
Use the standalone HTML file for maximum flexibility:

**File**: `public/next-to-win-standalone.html`
**URL**: `http://your-server-ip:8000/next-to-win-standalone.html`

**Features**:
- Completely standalone
- Configurable server URL
- Can be downloaded and used offline (with server connection)
- Works on any web server

## Setup Instructions

### 1. Start Your Laravel Server

```bash
cd /path/to/roulette-game
php artisan serve --host=0.0.0.0 --port=8000
```

**Important**: Use `--host=0.0.0.0` to allow access from other computers on the network.

### 2. Find Your Server IP Address

**On Windows**:
```cmd
ipconfig
```

**On macOS/Linux**:
```bash
ifconfig
```

Look for your local IP address (usually starts with 192.168.x.x or 10.x.x.x)

### 3. Access from Other Computers

**Option A - Laravel Route**:
```
http://YOUR_SERVER_IP:8000/next-to-win-display
```

**Option B - Standalone HTML**:
```
http://YOUR_SERVER_IP:8000/next-to-win-standalone.html
```

## API Endpoints

### Public API Endpoint
**URL**: `GET /api/next-to-win`

**Response**:
```json
{
    "success": true,
    "data": [
        {
            "name": "John Doe",
            "added_at": "Dec 15, 2023 2:30 PM",
            "added_by": "Admin"
        }
    ],
    "count": 1,
    "last_updated": "Dec 15, 2023 2:30 PM",
    "timestamp": 1702654200
}
```

## Usage Instructions

### For Administrators
1. Add names to the "Next to Win" list through the admin panel
2. The display will automatically update on all connected devices

### For Display Users
1. Open the display URL on any computer/device
2. The page will automatically refresh every 30 seconds
3. Shows connection status (Live/Offline)
4. Displays all names in the next-to-win list

### Standalone HTML Configuration
If using the standalone HTML file:
1. Open the file in a web browser
2. Enter your server's IP address and port in the configuration section
3. Click "Save & Connect"
4. The display will connect to your server

## Network Requirements

- **Same Network**: All devices must be on the same local network
- **Firewall**: Ensure port 8000 is not blocked by firewall
- **Laravel Server**: Must be running with `--host=0.0.0.0`

## Troubleshooting

### Connection Issues
1. **Check Server IP**: Make sure you're using the correct IP address
2. **Check Port**: Ensure port 8000 is accessible
3. **Check Firewall**: Disable firewall or allow port 8000
4. **Check Network**: Ensure all devices are on the same network

### Display Not Updating
1. **Check Console**: Open browser developer tools and check for errors
2. **Check Network Tab**: Verify API calls are successful
3. **Refresh Manually**: Use the refresh button in the bottom-right corner

### Server Not Accessible
1. **Restart Server**: Stop and restart the Laravel server
2. **Check Host Binding**: Ensure using `--host=0.0.0.0`
3. **Check Port**: Try a different port if 8000 is blocked

## Security Notes

- The API endpoint is public (no authentication required)
- Only displays data, cannot modify the next-to-win list
- Consider adding authentication if needed for production use

## Customization

### Change Refresh Interval
Edit the JavaScript in the HTML file:
```javascript
// Change from 30000ms (30 seconds) to your preferred interval
refreshInterval = setInterval(loadData, 30000);
```

### Change Display Styling
Modify the CSS in the `<style>` section of the HTML file.

### Add More Information
Extend the API response in `AdminController.php` and update the display logic accordingly.

## Files Created/Modified

1. **Routes**: `routes/web.php` - Added new routes
2. **Controller**: `app/Http/Controllers/AdminController.php` - Added API methods
3. **View**: `resources/views/next-to-win-display.blade.php` - Laravel view
4. **Standalone**: `public/next-to-win-standalone.html` - Standalone HTML file
5. **Documentation**: `NEXT_TO_WIN_DISPLAY_README.md` - This file

## Support

For issues or questions:
1. Check the browser console for JavaScript errors
2. Verify the server is running and accessible
3. Test the API endpoint directly: `http://your-server-ip:8000/api/next-to-win`
4. Ensure all devices are on the same network
