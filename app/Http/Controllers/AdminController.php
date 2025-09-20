<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Winner;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // Simple admin authentication check
        if (!session('admin_authenticated')) {
            return view('admin.login');
        }
        
        $stats = [
            'total_games' => Winner::count(),
            'unique_winners' => Winner::distinct('name')->count('name'),
            'average_players_per_game' => $this->getAveragePlayersPerGame(),
            'recent_winners' => Winner::orderBy('played_at', 'desc')->limit(10)->get()
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        // Simple hardcoded admin credentials (in production, use proper authentication)
        if ($request->username === 'admin' && $request->password === 'roulette2024') {
            session(['admin_authenticated' => true]);
            return redirect()->route('admin');
        }
        
        return redirect()->back()->withErrors(['login' => 'Invalid credentials']);
    }
    
    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('admin');
    }
    
    public function clearWinners()
    {
        if (!session('admin_authenticated')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        Winner::truncate();
        
        return response()->json(['message' => 'All winners cleared successfully']);
    }
    
    private function getAveragePlayersPerGame()
    {
        // This is a simplified calculation
        // In a real scenario, you might want to track this differently
        $totalGames = Winner::count();
        return $totalGames > 0 ? round($totalGames / max(1, $totalGames / 5), 2) : 0;
    }
}
