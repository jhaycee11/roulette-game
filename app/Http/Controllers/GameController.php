<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    
    
    
    
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

        // Create wheel sections (shuffle for visual randomness)
        $wheelSections = $players;
        shuffle($wheelSections);
        
        // Check for custom winner
        $customWinnerName = config('customwinner.winner_name', '');
        
        $winner = null;
        $winningSection = null;
        
        // If custom winner name is set and in the players list, use it
        if (!empty($customWinnerName) && in_array($customWinnerName, $players)) {
            $winner = $customWinnerName;
            $winningSection = array_search($customWinnerName, $wheelSections);
        } else {
            // Random selection from current players
            $winningSection = rand(0, count($players) - 1);
            $winner = $wheelSections[$winningSection];
        }
        
        return response()->json([
            'winner' => $winner,
            'winning_number' => $winningSection,
            'wheel_sections' => $wheelSections
        ]);
    }
    
}
