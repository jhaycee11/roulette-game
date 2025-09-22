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
        // Use Laravel's storage directory (more reliable for deployments)
        $path = storage_path('app/nexttowin.json');
        
        // Ensure storage/app directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        \Log::info('Next to Win file path', [
            'path' => $path,
            'storagePath' => storage_path(),
            'directoryExists' => is_dir(dirname($path)),
            'isWritable' => is_writable(dirname($path))
        ]);
        return $path;
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
            $result = File::put($filePath, $jsonData);
            
            // Verify the file was written successfully
            if ($result === false) {
                \Log::error('Failed to write Next to Win file', [
                    'filePath' => $filePath,
                    'directory' => $directory,
                    'writable' => is_writable($directory)
                ]);
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Exception saving Next to Win file', [
                'error' => $e->getMessage(),
                'filePath' => $filePath
            ]);
            return false;
        }
    }
    public function index()
    {
        // No authentication required - direct access to dashboard
        
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
        $request->validate([
            'winner_name' => 'required|string|max:255'
        ]);
        
        // Get existing next to win list from JSON file
        $nextToWin = $this->loadNextToWinFromFile();
        
        // Add the new name to the list
        $nextToWin[] = [
            'name' => $request->winner_name,
            'added_at' => now()->format('M d, Y h:i A'),
            'added_by' => 'Admin'
        ];
        
        // Save to JSON file
        $this->saveNextToWinToFile($nextToWin);
        
        return redirect()->route('admin')->with('success', 'Name added to Next to Win list!');
    }
    
    public function clearNextToWin()
    {
        
        try {
            $filePath = $this->getJsonFilePath();
            \Log::info('Attempting to clear Next to Win list', [
                'filePath' => $filePath,
                'fileExists' => File::exists($filePath),
                'directory' => dirname($filePath),
                'directoryExists' => File::exists(dirname($filePath)),
                'directoryWritable' => is_writable(dirname($filePath))
            ]);
            
            // Clear the JSON file
            $success = $this->saveNextToWinToFile([]);
            
            \Log::info('Clear Next to Win result', [
                'success' => $success,
                'fileExistsAfter' => File::exists($filePath),
                'fileContentAfter' => File::exists($filePath) ? File::get($filePath) : 'File not found'
            ]);
            
            if ($success) {
                return response()->json(['message' => 'Next to Win list cleared successfully']);
            } else {
                return response()->json(['error' => 'Failed to clear Next to Win list. Check file permissions.'], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error clearing Next to Win list', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'An error occurred while clearing the Next to Win list: ' . $e->getMessage()], 500);
        }
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
    
    /**
     * Public API endpoint to get next-to-win data
     * Accessible from any computer/IP without authentication
     */
    public function getNextToWin()
    {
        $nextToWin = $this->loadNextToWinFromFile();
        
        return response()->json([
            'success' => true,
            'data' => $nextToWin,
            'count' => count($nextToWin),
            'last_updated' => now()->format('M d, Y h:i A'),
            'timestamp' => now()->timestamp
        ]);
    }
    
    /**
     * Display page for next-to-win names
     * Simple frontend page that can be accessed from any computer/IP
     */
    public function nextToWinDisplay()
    {
        $nextToWin = $this->loadNextToWinFromFile();
        
        return view('next-to-win-display', compact('nextToWin'));
    }
    
    /**
     * POST API endpoint to check next-to-win data against current players
     * This is used by the game controller for winner selection
     */
    public function checkNextToWin(Request $request)
    {
        $nextToWin = $this->loadNextToWinFromFile();
        $players = $request->input('players', []);
        
        // Check which Next to Win names are in the current player list
        $availableNextToWin = [];
        $notInPlayers = [];
        
        foreach ($nextToWin as $item) {
            if (in_array($item['name'], $players)) {
                $availableNextToWin[] = $item['name'];
            } else {
                $notInPlayers[] = $item['name'];
            }
        }
        
        // If there are available next-to-win names, select one randomly
        $selectedWinner = null;
        if (!empty($availableNextToWin)) {
            $selectedWinner = $availableNextToWin[array_rand($availableNextToWin)];
        }
        
        return response()->json([
            'success' => true,
            'nextToWin' => $nextToWin,
            'players' => $players,
            'availableNextToWin' => $availableNextToWin,
            'notInPlayers' => $notInPlayers,
            'selectedWinner' => $selectedWinner,
            'hasNextToWin' => !empty($availableNextToWin),
            'count' => count($nextToWin),
            'availableCount' => count($availableNextToWin),
            'timestamp' => now()->timestamp
        ]);
    }
    
    /**
     * Remove a specific item from the next-to-win list by index
     */
    public function removeNextToWin($index)
    {
        
        try {
            $nextToWin = $this->loadNextToWinFromFile();
            
            // Validate index
            if (!isset($nextToWin[$index])) {
                return response()->json(['error' => 'Item not found'], 404);
            }
            
            // Remove the item
            $removedItem = $nextToWin[$index];
            unset($nextToWin[$index]);
            
            // Re-index the array
            $nextToWin = array_values($nextToWin);
            
            // Save back to file
            $success = $this->saveNextToWinToFile($nextToWin);
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed successfully',
                    'removedItem' => $removedItem,
                    'remainingCount' => count($nextToWin)
                ]);
            } else {
                return response()->json(['error' => 'Failed to save changes'], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error removing Next to Win item', [
                'error' => $e->getMessage(),
                'index' => $index
            ]);
            
            return response()->json(['error' => 'An error occurred while removing the item'], 500);
        }
    }
    
}
