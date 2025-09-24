<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CustomWinnerController extends Controller
{
    private function getConfigFilePath()
    {
        return config_path('customwinner.php');
    }
    
    private function loadCustomWinnerFromConfig()
    {
        $winnerName = config('customwinner.winner_name', '');
        $enabled = config('customwinner.enabled', false);
        
        return [
            'winner_name' => $winnerName,
            'enabled' => $enabled
        ];
    }
    
    private function saveCustomWinnerToConfig($winnerName, $enabled)
    {
        $filePath = $this->getConfigFilePath();
        
        // Create the config file content
        $configContent = "<?php\n\nreturn [\n    /*\n    |--------------------------------------------------------------------------\n    | Custom Winner Configuration\n    |--------------------------------------------------------------------------\n    |\n    | This file stores the name that should always win when present in the\n    | player list. If this name is in the players, it will be selected as\n    | the winner 100% of the time.\n    |\n    */\n    \n    'winner_name' => '" . addslashes($winnerName) . "',\n    'enabled' => " . ($enabled ? 'true' : 'false') . ",\n];\n";
        
        try {
            $result = File::put($filePath, $configContent);
            
            if ($result === false) {
                \Log::error('Failed to write Custom Winner config file', [
                    'filePath' => $filePath,
                    'writable' => is_writable(dirname($filePath))
                ]);
                return false;
            }
            
            // Clear config cache to reload the new values
            \Artisan::call('config:clear');
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Exception saving Custom Winner config file', [
                'error' => $e->getMessage(),
                'filePath' => $filePath
            ]);
            return false;
        }
    }
    
    public function index()
    {
        $customWinner = $this->loadCustomWinnerFromConfig();
        
        return view('custom-winner.index', compact('customWinner'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'winner_name' => 'required|string|max:255',
            'enabled' => 'boolean'
        ]);
        
        $winnerName = $request->input('winner_name', '');
        $enabled = $request->has('enabled') ? true : false;
        
        $success = $this->saveCustomWinnerToConfig($winnerName, $enabled);
        
        if ($success) {
            return redirect()->route('custom-winner.index')
                ->with('success', 'Custom winner settings updated successfully!');
        } else {
            return redirect()->route('custom-winner.index')
                ->with('error', 'Failed to update custom winner settings. Please check file permissions.');
        }
    }
    
    public function clear()
    {
        $success = $this->saveCustomWinnerToConfig('', false);
        
        if ($success) {
            return redirect()->route('custom-winner.index')
                ->with('success', 'Custom winner cleared successfully!');
        } else {
            return redirect()->route('custom-winner.index')
                ->with('error', 'Failed to clear custom winner. Please check file permissions.');
        }
    }
    
    /**
     * API endpoint to get custom winner data
     */
    public function getCustomWinner()
    {
        $customWinner = $this->loadCustomWinnerFromConfig();
        
        return response()->json([
            'success' => true,
            'data' => $customWinner,
            'last_updated' => now()->format('M d, Y h:i A'),
            'timestamp' => now()->timestamp
        ]);
    }
}
