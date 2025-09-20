<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'total_games' => 0,
            'unique_winners' => 0,
            'average_players_per_game' => 0,
            'recent_winners' => collect([])
        ];
        
        $nextToWin = session('next_to_win', []);
        
        return view('admin.dashboard', compact('stats', 'nextToWin'));
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        // Admin credentials: jhaycee and dessa with password "password"
        $validCredentials = [
            'jhaycee' => 'password',
            'dessa' => 'password'
        ];
        
        if (isset($validCredentials[$request->username]) && 
            $validCredentials[$request->username] === $request->password) {
            session(['admin_authenticated' => true, 'admin_user' => $request->username]);
            return redirect()->route('admin');
        }
        
        return redirect()->back()->withErrors(['login' => 'Invalid credentials']);
    }
    
    public function logout()
    {
        session()->forget(['admin_authenticated', 'admin_user']);
        return redirect()->route('admin');
    }
    
    public function addWin(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin');
        }
        
        $request->validate([
            'winner_name' => 'required|string|max:255'
        ]);
        
        // Get existing next to win list from session
        $nextToWin = session('next_to_win', []);
        
        // Add the new name to the list
        $nextToWin[] = [
            'name' => $request->winner_name,
            'added_at' => now()->format('M d, Y h:i A'),
            'added_by' => session('admin_user', 'Admin')
        ];
        
        // Store back to session
        session(['next_to_win' => $nextToWin]);
        
        return redirect()->route('admin')->with('success', 'Name added to Next to Win list!');
    }
    
    public function clearNextToWin()
    {
        if (!session('admin_authenticated')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        session()->forget('next_to_win');
        
        return response()->json(['message' => 'Next to Win list cleared successfully']);
    }
    
}
