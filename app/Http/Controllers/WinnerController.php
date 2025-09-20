<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Winner;

class WinnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Winner::orderBy('played_at', 'desc');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Sort functionality
        $sortBy = $request->get('sort', 'played_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['name', 'winning_number', 'played_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $winners = $query->paginate(20);
        
        return view('winners', compact('winners'));
    }
}
