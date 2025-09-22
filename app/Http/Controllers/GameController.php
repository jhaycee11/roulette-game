<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    private function loadNextToWinFromFile()
    {
        // HTML List implementation - read from localStorage via JavaScript
        // This method is kept for compatibility but will be overridden by frontend
        return [];
    }
    
    /**
     * Get next-to-win data from API endpoint
     * This replaces direct JSON file reading for better consistency
     */
    private function getNextToWinFromAPI()
    {
        try {
            // Get the current request's base URL to construct the API URL
            $baseUrl = request()->getSchemeAndHttpHost();
            $apiUrl = $baseUrl . '/api/next-to-win';
            
            // Make HTTP request to our own API
            $response = \Http::get($apiUrl);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                    return $data['data'];
                }
            }
            
            \Log::warning('Failed to get next-to-win data from API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            // Fallback to direct file reading if API fails
            return $this->loadNextToWinFromFile();
            
        } catch (\Exception $e) {
            \Log::error('Exception getting next-to-win data from API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback to direct file reading if API fails
            return $this->loadNextToWinFromFile();
        }
    }
    
    /**
     * Check next-to-win data against current players using POST API
     * This is the new preferred method for winner selection
     */
    private function checkNextToWinWithAPI($players)
    {
        try {
            // Get the current request's base URL to construct the API URL
            $baseUrl = request()->getSchemeAndHttpHost();
            $apiUrl = $baseUrl . '/api/next-to-win/check';
            
            // Make POST request to our API with players data
            $response = \Http::post($apiUrl, [
                'players' => $players
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success']) {
                    return $data;
                }
            }
            
            \Log::warning('Failed to check next-to-win data from API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            // Fallback to direct file reading if API fails
            return $this->fallbackNextToWinCheck($players);
            
        } catch (\Exception $e) {
            \Log::error('Exception checking next-to-win data from API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback to direct file reading if API fails
            return $this->fallbackNextToWinCheck($players);
        }
    }
    
    /**
     * Fallback method when API is not available
     */
    private function fallbackNextToWinCheck($players)
    {
        $nextToWin = $this->loadNextToWinFromFile();
        $availableNextToWin = [];
        
        foreach ($nextToWin as $item) {
            if (in_array($item['name'], $players)) {
                $availableNextToWin[] = $item['name'];
            }
        }
        
        $selectedWinner = null;
        if (!empty($availableNextToWin)) {
            $selectedWinner = $availableNextToWin[array_rand($availableNextToWin)];
        }
        
        return [
            'success' => true,
            'nextToWin' => $nextToWin,
            'players' => $players,
            'availableNextToWin' => $availableNextToWin,
            'selectedWinner' => $selectedWinner,
            'hasNextToWin' => !empty($availableNextToWin),
            'count' => count($nextToWin),
            'availableCount' => count($availableNextToWin),
            'timestamp' => now()->timestamp,
            'source' => 'fallback'
        ];
    }
    
    /**
     * Direct method to check next-to-win data (optimized, no HTTP calls)
     * This is the preferred method for performance
     */
    private function checkNextToWinDirect($players)
    {
        $nextToWin = $this->loadNextToWinFromFile();
        $availableNextToWin = [];
        $notInPlayers = [];
        
        // Check which Next to Win names are in the current player list
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
        
        \Log::info('Direct Next to Win Check', [
            'nextToWin' => $nextToWin,
            'players' => $players,
            'availableNextToWin' => $availableNextToWin,
            'selectedWinner' => $selectedWinner,
            'hasNextToWin' => !empty($availableNextToWin)
        ]);
        
        return [
            'success' => true,
            'nextToWin' => $nextToWin,
            'players' => $players,
            'availableNextToWin' => $availableNextToWin,
            'notInPlayers' => $notInPlayers,
            'selectedWinner' => $selectedWinner,
            'hasNextToWin' => !empty($availableNextToWin),
            'count' => count($nextToWin),
            'availableCount' => count($availableNextToWin),
            'timestamp' => now()->timestamp,
            'source' => 'direct'
        ];
    }
    public function index()
    {
        return view('home');
    }

    public function storePlayers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'players_input' => 'string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Parse the textarea input - split by newlines and filter empty lines
        $playersInput = $request->input('players_input', '');
        $allPlayers = array_filter(
            array_map('trim', explode("\n", $playersInput)),
            function($name) {
                return !empty($name);
            }
        );
        
        // If no players entered, redirect back with message
        if (empty($allPlayers)) {
            return redirect()->back()
                ->withErrors(['players_input' => 'Please enter at least one player name.'])
                ->withInput();
        }
        
        // Take only the first 1,000 players if more are entered
        $players = array_slice($allPlayers, 0, 1000);
        
        // No validation needed - accept all entries as-is
        // No session storage - players are handled in frontend only
        
        return redirect()->route('home');
    }


    public function spin(Request $request)
    {
        // Get players from request (sent from frontend)
        $players = $request->input('players', []);
        
        if (!$players || empty($players)) {
            return response()->json(['error' => 'No players found'], 400);
        }

        // Check next-to-win data using direct method call (optimized approach)
        $nextToWinData = $this->checkNextToWinDirect($players);
        $nextToWinUsed = $nextToWinData['hasNextToWin'];
        $targetWinner = $nextToWinData['selectedWinner'];
        
        // Debug logging
        \Log::info('Next to Win Debug (Direct)', [
            'nextToWinData' => $nextToWinData,
            'nextToWinUsed' => $nextToWinUsed,
            'targetWinner' => $targetWinner,
            'source' => 'Direct'
        ]);
        
        // Create wheel sections (shuffle for visual randomness)
        $wheelSections = $players;
        shuffle($wheelSections);
        
        // Determine the winning section and winner
        if ($nextToWinUsed && $targetWinner) {
            // Find the target winner's position in the shuffled wheel
            $winningSection = array_search($targetWinner, $wheelSections);
            $winner = $targetWinner;
        } else {
            // Random selection from current players
            $winningSection = rand(0, count($players) - 1);
            $winner = $wheelSections[$winningSection];
        }
        
        return response()->json([
            'winner' => $winner,
            'winning_number' => $winningSection,
            'wheel_sections' => $wheelSections,
            'next_to_win_used' => $nextToWinUsed,
            'debug_info' => [
                'source' => $nextToWinData['source'] ?? 'Direct',
                'available_count' => $nextToWinData['availableCount'] ?? 0,
                'total_count' => $nextToWinData['count'] ?? 0,
                'target_winner' => $targetWinner,
                'available_names' => $nextToWinData['availableNextToWin'] ?? []
            ]
        ]);
    }
}
