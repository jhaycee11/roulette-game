<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Next to Win Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file manages the "Next to Win" feature for the
    | roulette game. Names listed here will be guaranteed to win when
    | they appear in the player list.
    |
    */

    'enabled' => true,

    'names' => [
        // Add names here that should be guaranteed to win
        // Example: 'John Doe', 'Jane Smith', 'Player Name'
        'DESSA', // Example name from previous JSON file
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration Notes
    |--------------------------------------------------------------------------
    |
    | - Set 'enabled' to false to disable the Next to Win feature
    | - Add player names to the 'names' array to guarantee they win
    | - Names are case-sensitive and must match exactly
    | - Empty array means no guaranteed winners (normal random selection)
    |
    */
];
