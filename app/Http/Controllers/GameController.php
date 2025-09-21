<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class GameController extends Controller
{
    private function loadNextToWinFromFile()
    {
        $filePath = public_path('storage/save/nexttowin.json');
        
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

        // Check if there's a "Next to Win" list and if any names are in the current players list
        $nextToWin = $this->loadNextToWinFromFile();
        $nextToWinUsed = false;
        $targetWinner = null;
        
        // Always check if any "Next to Win" names exist in the current players list
        if (!empty($nextToWin)) {
            $availableNextToWin = [];
            foreach ($nextToWin as $nextToWinEntry) {
                if (in_array($nextToWinEntry['name'], $players)) {
                    $availableNextToWin[] = $nextToWinEntry['name'];
                }
            }
            
            // If any "Next to Win" names are in the players list, guarantee one of them wins
            if (!empty($availableNextToWin)) {
                $nextToWinUsed = true;
                $targetWinner = $availableNextToWin[rand(0, count($availableNextToWin) - 1)];
            }
        }
        
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
            'next_to_win_used' => $nextToWinUsed
        ]);
    }
}
