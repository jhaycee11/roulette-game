<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Winners - Roulette Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            padding: 3rem 0;
            color: white;
            text-align: center;
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .winners-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            margin: 2rem 0;
        }
        
        .search-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .winners-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f8f9fa;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .winner-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .winning-number {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .sort-link {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .sort-link:hover {
            color: #ffd700;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .btn-secondary {
            background: linear-gradient(45deg, #6c757d, #495057);
            border: none;
            border-radius: 50px;
            padding: 0.8rem 2rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
            color: white;
        }
        
        .no-winners {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .no-winners i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }
        
        .page-link {
            color: #667eea;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin: 0 0.2rem;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            background-color: #667eea;
            border-color: #667eea;
            color: white;
        }
        
        .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .winners-card {
                padding: 1rem;
                margin: 1rem 0;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero-section">
            <h1 class="hero-title">
                <i class="fas fa-trophy"></i> Past Winners
            </h1>
            <p class="text-white-50">See who has won the roulette game!</p>
        </div>
        
        <div class="winners-card">
            <div class="search-container">
                <form method="GET" action="{{ route('winners') }}" class="row g-3">
                    <div class="col-md-8">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by winner name..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
            
            @if($winners->count() > 0)
                <div class="winners-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="sort-link">
                                        <i class="fas fa-user"></i> Winner Name
                                        @if(request('sort') === 'name')
                                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'winning_number', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="sort-link">
                                        <i class="fas fa-dice"></i> Winning Number
                                        @if(request('sort') === 'winning_number')
                                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'played_at', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="sort-link">
                                        <i class="fas fa-clock"></i> Date & Time
                                        @if(request('sort') === 'played_at')
                                            <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($winners as $winner)
                                <tr>
                                    <td>
                                        <span class="winner-badge">{{ $winner->name }}</span>
                                    </td>
                                    <td>
                                        <span class="winning-number">{{ $winner->winning_number }}</span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted me-2"></i>
                                        {{ $winner->played_at->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $winner->played_at->format('h:i A') }}
                                        </small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $winners->appends(request()->query())->links() }}
                </div>
            @else
                <div class="no-winners">
                    <i class="fas fa-trophy"></i>
                    <h4>No Winners Yet</h4>
                    <p>Be the first to play and win the roulette game!</p>
                </div>
            @endif
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> Play Game
            </a>
            <a href="{{ route('admin') }}" class="btn btn-secondary">
                <i class="fas fa-cog"></i> Admin Panel
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
