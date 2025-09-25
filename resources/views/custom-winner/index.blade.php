<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Winner Settings - Roulette Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .settings-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
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
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-check {
            margin-bottom: 20px;
        }
        
        .form-check-input {
            width: 20px;
            height: 20px;
            margin-top: 0.25rem;
        }
        
        .form-check-label {
            font-size: 1.1rem;
            color: #333;
            margin-left: 10px;
        }
        
        .btn {
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            border: none;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #868e96);
            border: none;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .current-settings {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .current-settings h5 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .status-enabled {
            background: #d4edda;
            color: #155724;
        }
        
        .status-disabled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: #f8f9fa;
            transform: translateX(-5px);
        }
        
        .info-box {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .info-box h6 {
            color: #1976d2;
            margin-bottom: 10px;
        }
        
        .info-box p {
            color: #1565c0;
            margin-bottom: 5px;
        }
        
        @media (max-width: 768px) {
            .settings-card {
                padding: 20px;
                margin: 10px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .back-link {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 20px;
                display: inline-block;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('home') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Game
    </a>
    
    <div class="main-container">
        <div class="settings-card">
            <div class="header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1><i class="fas"></i>🩻🩻🩻</h1>
                    <div>
                        <span class="text-muted me-3">Welcome, {{ Auth::guard('static')->user()->name }}!</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="current-settings">
                    @if($customWinner['winner_name'])
                     <h3>🤑🤑🤑{{ $customWinner['winner_name'] }}🤑🤑🤑</h3> 
                    @endif
                </p>
            </div>
            
            
            <form method="POST" action="{{ route('custom-winner.update') }}">
                @csrf
                
                <div class="form-group">
                    <label for="winner_name" class="form-label">
                        <i class="fas fa-user-crown"></i> Super User
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="winner_name" 
                           name="winner_name" 
                           value="{{ old('winner_name', $customWinner['winner_name']) }}"
                           placeholder="Do not write your name 💀"
                           required>
                    <small class="form-text text-muted">
                    </small>
                </div>
                
                
                <div class="d-flex flex-wrap">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                    
                    <a href="{{ route('custom-winner.clear') }}" 
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to clear the custom winner?')">
                        <i class="fas fa-trash"></i> Clear Winner
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
