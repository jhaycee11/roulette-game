<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Roulette Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        
        .admin-title {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }
        
        .stats-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .recent-winners-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .winner-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .winner-item:last-child {
            border-bottom: none;
        }
        
        .winner-name {
            font-weight: 600;
            color: #333;
        }
        
        .winner-number {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .winner-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .btn-danger {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(45deg, #6c757d, #495057);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        
        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }
        
        
        @media (max-width: 768px) {
            .admin-title {
                font-size: 1.5rem;
            }
            
            .stats-card {
                margin-bottom: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="admin-title">
                        <i class="fas fa-shield-alt"></i> Admin Dashboard
                        @if(session('admin_user'))
                            <small class="d-block" style="font-size: 0.6em; font-weight: normal; opacity: 0.8;">
                                Logged in as: {{ session('admin_user') }}
                            </small>
                        @endif
                    </h1>
                </div>
                <div class="col-md-4 text-end">
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="recent-winners-card">
                    <h4 class="mb-3">
                        <i class="fas fa-cog"></i> Admin Actions
                    </h4>
                    
                    <!-- HTML List Management Section -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-star"></i> Next to Win List (Server-Side)
                        </h5>
                        <p class="text-muted mb-3">Manage the list of names that should win next. This uses server-side storage and is accessible from any device/browser.</p>
                        
                        <!-- Add New Name Form -->
                        <form id="addForm" class="mb-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" 
                                           id="newName" 
                                           class="form-control" 
                                           placeholder="Enter name to add to Next to Win list"
                                           required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-plus"></i> Add to List
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- List Display -->
                        <div id="listContainer">
                            <div id="emptyState" class="text-center text-muted py-3" style="display: none;">
                                <i class="fas fa-list fa-2x mb-2"></i>
                                <p class="mb-0">No names in Next to Win list</p>
                                <small>Add some names above to get started</small>
                            </div>
                            <div id="nameList"></div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-3">
                            <button id="clearAllBtn" class="btn btn-outline-danger me-2" style="display: none;">
                                <i class="fas fa-trash"></i> Clear All
                            </button>
                            <button class="btn btn-outline-info" onclick="refreshList()">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Server-side List Management
        let nextToWinList = [];
        
        // CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Load list from server
        async function loadListFromServer() {
            try {
                const response = await fetch('/admin/next-to-win', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    nextToWinList = data.data || [];
                    displayList();
                } else {
                    showMessage('Failed to load list from server', 'danger');
                }
            } catch (error) {
                console.error('Error loading list:', error);
                showMessage('Error connecting to server', 'danger');
            }
        }
        
        // Display the list
        function displayList() {
            const nameList = document.getElementById('nameList');
            const emptyState = document.getElementById('emptyState');
            const clearAllBtn = document.getElementById('clearAllBtn');
            
            if (nextToWinList.length === 0) {
                nameList.innerHTML = '';
                emptyState.style.display = 'block';
                clearAllBtn.style.display = 'none';
            } else {
                emptyState.style.display = 'none';
                clearAllBtn.style.display = 'inline-block';
                
                nameList.innerHTML = nextToWinList.map((item, index) => `
                    <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-light rounded">
                        <div>
                            <strong>${item.name}</strong>
                            <small class="text-muted d-block">Added: ${item.added_at} by ${item.added_by}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeName(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `).join('');
            }
        }
        
        // Add new name to server
        document.getElementById('addForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const newName = document.getElementById('newName').value.trim();
            
            if (!newName) {
                showMessage('Please enter a name', 'warning');
                return;
            }
            
            // Check if name already exists
            if (nextToWinList.some(item => item.name.toLowerCase() === newName.toLowerCase())) {
                showMessage('This name is already in the list!', 'warning');
                return;
            }
            
            try {
                const response = await fetch('/admin/add-win', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        winner_name: newName
                    })
                });
                
                if (response.ok) {
                    document.getElementById('newName').value = '';
                    await loadListFromServer(); // Reload from server
                    showMessage('Name added successfully!', 'success');
                } else {
                    const error = await response.json();
                    showMessage(error.message || 'Failed to add name', 'danger');
                }
            } catch (error) {
                console.error('Error adding name:', error);
                showMessage('Error connecting to server', 'danger');
            }
        });
        
        // Remove name from server
        async function removeName(index) {
            if (!confirm('Are you sure you want to remove this name?')) {
                return;
            }
            
            try {
                const response = await fetch(`/admin/next-to-win/${index}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    await loadListFromServer(); // Reload from server
                    showMessage('Name removed successfully!', 'info');
                } else {
                    const error = await response.json();
                    showMessage(error.message || 'Failed to remove name', 'danger');
                }
            } catch (error) {
                console.error('Error removing name:', error);
                showMessage('Error connecting to server', 'danger');
            }
        }
        
        // Clear all names from server
        document.getElementById('clearAllBtn').addEventListener('click', async function() {
            if (!confirm('Are you sure you want to clear all names from the Next to Win list?')) {
                return;
            }
            
            try {
                const response = await fetch('/admin/next-to-win/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    await loadListFromServer(); // Reload from server
                    showMessage('All names cleared!', 'info');
                } else {
                    const error = await response.json();
                    showMessage(error.message || 'Failed to clear list', 'danger');
                }
            } catch (error) {
                console.error('Error clearing list:', error);
                showMessage('Error connecting to server', 'danger');
            }
        });
        
        // Refresh list from server
        async function refreshList() {
            await loadListFromServer();
            showMessage('List refreshed!', 'info');
        }
        
        // Show message
        function showMessage(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('.recent-winners-card');
            container.insertBefore(alertDiv, container.firstChild);
            
            // Auto-remove after 3 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }
        
        // Initialize display
        loadListFromServer();
    </script>
</body>
</html>
