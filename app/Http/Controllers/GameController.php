<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function index()
    {
        $players = Session::get('players');
        return view('home', compact('players'));
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

        Session::put('players', $players);
        
        return redirect()->route('home');
    }


    public function spin(Request $request)
    {
        $players = Session::get('players');
        
        if (!$players) {
            return response()->json(['error' => 'No players found'], 400);
        }

        // Create wheel sections based on number of players
        $totalSections = count($players);
        $wheelSections = $players;
        
        // Shuffle the assignments to make it more random
        shuffle($wheelSections);
        
        // Select winning section
        $winningSection = rand(0, $totalSections - 1);
        $winner = $wheelSections[$winningSection];
        
        return response()->json([
            'winner' => $winner,
            'winning_number' => $winningSection,
            'wheel_sections' => $wheelSections
        ]);
    }
}
