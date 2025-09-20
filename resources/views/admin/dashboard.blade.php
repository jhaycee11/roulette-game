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
        
        .clear-winners-btn {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .clear-winners-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
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
                    <a href="{{ route('admin.logout') }}" class="btn logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-icon text-primary">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stats-number">{{ $stats['total_games'] }}</div>
                    <div class="stats-label">Total Games</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-icon text-success">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number">{{ $stats['unique_winners'] }}</div>
                    <div class="stats-label">Unique Winners</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-icon text-warning">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-number">{{ $stats['average_players_per_game'] }}</div>
                    <div class="stats-label">Avg Players/Game</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-icon text-info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number">{{ $stats['recent_winners']->count() }}</div>
                    <div class="stats-label">Recent Winners</div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="recent-winners-card">
                    <h4 class="mb-3">
                        <i class="fas fa-list"></i> Recent Winners
                    </h4>
                    
                    @if($stats['recent_winners']->count() > 0)
                        @foreach($stats['recent_winners'] as $winner)
                            <div class="winner-item">
                                <div>
                                    <div class="winner-name">{{ $winner->name }}</div>
                                    <div class="winner-date">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $winner->played_at->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                                <div class="winner-number">{{ $winner->winning_number }}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-trophy fa-3x mb-3"></i>
                            <p>No recent winners to display</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="recent-winners-card">
                    <h4 class="mb-3">
                        <i class="fas fa-cog"></i> Admin Actions
                    </h4>
                    
                    <div class="d-grid gap-2">
                        <button class="btn clear-winners-btn" onclick="clearWinners()">
                            <i class="fas fa-trash"></i> Clear All Winners
                        </button>
                        
                        <a href="{{ route('winners') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> View All Winners
                        </a>
                        
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Back to Game
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function clearWinners() {
            if (confirm('Are you sure you want to clear all winners? This action cannot be undone.')) {
                fetch('{{ route("admin.clear.winners") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        location.reload();
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while clearing winners.');
                });
            }
        }
    </script>
</body>
</html>
