<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Next to Win - Live Display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }
        
        .display-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .header h1 {
            color: #333;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 1.1rem;
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .status-indicator.live {
            background-color: #28a745;
            animation: pulse 2s infinite;
        }
        
        .status-indicator.offline {
            background-color: #dc3545;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .names-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .name-card {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            box-shadow: 0 8px 16px rgba(255, 107, 107, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .name-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(255, 107, 107, 0.4);
        }
        
        .name-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.5s;
        }
        
        .name-card:hover::before {
            animation: shine 0.8s ease-in-out;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .name-card .name {
            position: relative;
            z-index: 1;
        }
        
        .name-card .added-info {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-top: 8px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ccc;
        }
        
        .empty-state h3 {
            margin-bottom: 10px;
            color: #888;
        }
        
        .stats-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .last-updated {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-top: 20px;
        }
        
        .refresh-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .refresh-btn:hover {
            background: #5a6fd8;
            transform: scale(1.1);
        }
        
        .refresh-btn:active {
            transform: scale(0.95);
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .loading i {
            font-size: 2rem;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .display-container {
                padding: 20px;
                margin: 10px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .names-grid {
                grid-template-columns: 1fr;
            }
            
            .refresh-btn {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="display-container">
        <div class="header">
            <h1><i class="fas fa-trophy"></i> Next to Win</h1>
            <div class="subtitle">
                <span class="status-indicator" id="statusIndicator"></span>
                <span id="statusText">Loading...</span>
            </div>
        </div>
        
        <div id="loadingIndicator" class="loading">
            <i class="fas fa-spinner"></i>
            <p>Loading next-to-win data...</p>
        </div>
        
        <div id="errorContainer" style="display: none;"></div>
        
        <div id="contentContainer" style="display: none;">
            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number" id="totalCount">0</div>
                        <div class="stat-label">Total Names</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="lastUpdateTime">--:--</div>
                        <div class="stat-label">Last Updated</div>
                    </div>
                </div>
            </div>
            
            <div id="namesContainer">
                <!-- Names will be populated here -->
            </div>
            
            <div class="last-updated">
                <i class="fas fa-clock"></i>
                <span id="lastUpdatedText">Last updated: Never</span>
            </div>
        </div>
    </div>
    
    <button class="refresh-btn" onclick="refreshData()" title="Refresh Data">
        <i class="fas fa-sync-alt"></i>
    </button>
    
    <script>
        let refreshInterval;
        let isOnline = false;
        
        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            // Auto-refresh every 30 seconds
            refreshInterval = setInterval(loadData, 30000);
        });
        
        // Load data from API
        async function loadData() {
            try {
                updateStatus('Loading...', false);
                showLoading(true);
                hideError();
                
                const response = await fetch('/api/next-to-win');
                const data = await response.json();
                
                if (data.success) {
                    displayData(data);
                    updateStatus('Live', true);
                    isOnline = true;
                } else {
                    throw new Error('Failed to load data');
                }
            } catch (error) {
                console.error('Error loading data:', error);
                showError('Failed to load next-to-win data. Please check your connection.');
                updateStatus('Offline', false);
                isOnline = false;
            } finally {
                showLoading(false);
            }
        }
        
        // Display the data
        function displayData(data) {
            const namesContainer = document.getElementById('namesContainer');
            const totalCount = document.getElementById('totalCount');
            const lastUpdateTime = document.getElementById('lastUpdateTime');
            const lastUpdatedText = document.getElementById('lastUpdatedText');
            
            // Update stats
            totalCount.textContent = data.count;
            lastUpdateTime.textContent = new Date(data.timestamp * 1000).toLocaleTimeString();
            lastUpdatedText.textContent = `Last updated: ${data.last_updated}`;
            
            // Clear previous content
            namesContainer.innerHTML = '';
            
            if (data.data && data.data.length > 0) {
                // Create names grid
                const namesGrid = document.createElement('div');
                namesGrid.className = 'names-grid';
                
                data.data.forEach((item, index) => {
                    const nameCard = document.createElement('div');
                    nameCard.className = 'name-card';
                    nameCard.innerHTML = `
                        <div class="name">${item.name}</div>
                        <div class="added-info">
                            Added by ${item.added_by}<br>
                            ${item.added_at}
                        </div>
                    `;
                    namesGrid.appendChild(nameCard);
                });
                
                namesContainer.appendChild(namesGrid);
            } else {
                // Show empty state
                const emptyState = document.createElement('div');
                emptyState.className = 'empty-state';
                emptyState.innerHTML = `
                    <i class="fas fa-list"></i>
                    <h3>No names in Next to Win list</h3>
                    <p>Names will appear here when added by an administrator.</p>
                `;
                namesContainer.appendChild(emptyState);
            }
            
            // Show content
            document.getElementById('contentContainer').style.display = 'block';
        }
        
        // Update status indicator
        function updateStatus(text, isLive) {
            const statusIndicator = document.getElementById('statusIndicator');
            const statusText = document.getElementById('statusText');
            
            statusText.textContent = text;
            statusIndicator.className = `status-indicator ${isLive ? 'live' : 'offline'}`;
        }
        
        // Show/hide loading indicator
        function showLoading(show) {
            document.getElementById('loadingIndicator').style.display = show ? 'block' : 'none';
        }
        
        // Show error message
        function showError(message) {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    ${message}
                </div>
            `;
            errorContainer.style.display = 'block';
        }
        
        // Hide error message
        function hideError() {
            document.getElementById('errorContainer').style.display = 'none';
        }
        
        // Manual refresh
        function refreshData() {
            const refreshBtn = document.querySelector('.refresh-btn i');
            refreshBtn.style.animation = 'spin 1s linear infinite';
            
            loadData().finally(() => {
                setTimeout(() => {
                    refreshBtn.style.animation = '';
                }, 1000);
            });
        }
        
        // Handle page visibility change
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(refreshInterval);
            } else {
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                }
                refreshInterval = setInterval(loadData, 30000);
                loadData(); // Load immediately when page becomes visible
            }
        });
        
        // Handle online/offline events
        window.addEventListener('online', function() {
            if (!isOnline) {
                loadData();
            }
        });
        
        window.addEventListener('offline', function() {
            updateStatus('Offline', false);
            isOnline = false;
        });
    </script>
</body>
</html>
