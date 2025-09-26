<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Winner Settings - Roulette Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/custom-winner.css', 'resources/js/app.js'])
</head>
<body>    
    <div class="main-container">
        <div class="settings-card">
            <div class="header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1><i class="fas"></i>💀💀💀</h1>
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
                <p><strong>SU:</strong> 
                    @if($customWinner['winner_name'])
                        "{{ $customWinner['winner_name'] }}"
                    @else
                        <em>No super user set</em>
                    @endif
                </p>
            </div>
           
            
            <form method="POST" action="{{ route('custom-winner.update') }}">
                @csrf
                
                <div class="form-group">
                    <label for="winner_name" class="form-label">
                        <i class="fas fa-user-crown"></i> SU
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="winner_name" 
                           name="winner_name" 
                           value="{{ old('winner_name', $customWinner['winner_name']) }}"
                           placeholder="Do not try to add your name 💀"
                           required>
                </div>
                
                
                <div class="d-flex flex-wrap">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                    
                    <a href="{{ route('custom-winner.clear') }}" 
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to clear the custom winner?')">
                        <i class="fas fa-trash"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
