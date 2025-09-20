<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    private function getJsonFilePath()
    {
        return public_path('storage/save/nexttowin.json');
    }
    
    private function loadNextToWinFromFile()
    {
        $filePath = $this->getJsonFilePath();
        
        if (!File::exists($filePath)) {
            return [];
        }
        
        try {
            $content = File::get($filePath);
            $data = json_decode($content, true);
            return is_array($data) ? $data : [];
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function saveNextToWinToFile($nextToWin)
    {
        $filePath = $this->getJsonFilePath();
        
        // Ensure directory exists
        $directory = dirname($filePath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        try {
            $jsonData = json_encode($nextToWin, JSON_PRETTY_PRINT);
            File::put($filePath, $jsonData);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
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
        
        $nextToWin = $this->loadNextToWinFromFile();
        
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
        
        // Get existing next to win list from JSON file
        $nextToWin = $this->loadNextToWinFromFile();
        
        // Add the new name to the list
        $nextToWin[] = [
            'name' => $request->winner_name,
            'added_at' => now()->format('M d, Y h:i A'),
            'added_by' => session('admin_user', 'Admin')
        ];
        
        // Save to JSON file
        $this->saveNextToWinToFile($nextToWin);
        
        return redirect()->route('admin')->with('success', 'Name added to Next to Win list!');
    }
    
    public function clearNextToWin()
    {
        if (!session('admin_authenticated')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Clear the JSON file
        $this->saveNextToWinToFile([]);
        
        return response()->json(['message' => 'Next to Win list cleared successfully']);
    }
    
    public function debugNextToWin(Request $request)
    {
        $nextToWin = $this->loadNextToWinFromFile();
        $players = $request->input('players', []);
        
        // Check which Next to Win names are in the current player list
        $comparison = [];
        $availableInPlayers = [];
        $notInPlayers = [];
        
        foreach ($nextToWin as $index => $item) {
            $isInPlayers = in_array($item['name'], $players);
            $comparison[] = [
                'name' => $item['name'],
                'added_by' => $item['added_by'],
                'added_at' => $item['added_at'],
                'is_in_players' => $isInPlayers,
                'index' => $index + 1
            ];
            
            if ($isInPlayers) {
                $availableInPlayers[] = $item['name'];
            } else {
                $notInPlayers[] = $item['name'];
            }
        }
        
        return response()->json([
            'nextToWin' => $nextToWin,
            'players' => $players,
            'count' => count($nextToWin),
            'playerCount' => count($players),
            'comparison' => $comparison,
            'availableInPlayers' => $availableInPlayers,
            'notInPlayers' => $notInPlayers,
            'availableCount' => count($availableInPlayers),
            'notAvailableCount' => count($notInPlayers)
        ]);
    }
    
}
