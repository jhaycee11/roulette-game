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
                            <i class="fas fa-star"></i> Next to Win List (HTML List)
                        </h5>
                        <p class="text-muted mb-3">Manage the list of names that should win next. This uses browser localStorage for reliable storage.</p>
                        
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
        // HTML List Management
        let nextToWinList = JSON.parse(localStorage.getItem('nextToWinList') || '[]');
        
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
                
                nameList.innerHTML = nextToWinList.map((name, index) => `
                    <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-light rounded">
                        <div>
                            <strong>${name}</strong>
                            <small class="text-muted d-block">Added: ${new Date().toLocaleDateString()}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeName(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `).join('');
            }
        }
        
        // Add new name
        document.getElementById('addForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const newName = document.getElementById('newName').value.trim();
            
            if (newName && !nextToWinList.includes(newName)) {
                nextToWinList.push(newName);
                localStorage.setItem('nextToWinList', JSON.stringify(nextToWinList));
                document.getElementById('newName').value = '';
                displayList();
                showMessage('Name added successfully!', 'success');
            } else if (nextToWinList.includes(newName)) {
                showMessage('This name is already in the list!', 'warning');
            }
        });
        
        // Remove name
        function removeName(index) {
            const removedName = nextToWinList[index];
            nextToWinList.splice(index, 1);
            localStorage.setItem('nextToWinList', JSON.stringify(nextToWinList));
            displayList();
            showMessage(`"${removedName}" removed from list`, 'info');
        }
        
        // Clear all names
        document.getElementById('clearAllBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all names from the Next to Win list?')) {
                nextToWinList = [];
                localStorage.setItem('nextToWinList', JSON.stringify(nextToWinList));
                displayList();
                showMessage('All names cleared!', 'info');
            }
        });
        
        // Refresh list
        function refreshList() {
            nextToWinList = JSON.parse(localStorage.getItem('nextToWinList') || '[]');
            displayList();
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
        displayList();
    </script>
</body>
</html>
