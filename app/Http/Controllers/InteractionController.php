<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get interactions for this user's clients
        $interactions = Interaction::with(['client', 'analysis'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15);
            
        return response()->json([
            'data' => $interactions->items(),
            'meta' => [
                'current_page' => $interactions->currentPage(),
                'last_page' => $interactions->lastPage(),
                'total' => $interactions->total(),
            ]
        ]);
    }

    public function show(Interaction $interaction)
    {
        $interaction->load(['client', 'analysis']);
        
        return response()->json([
            'data' => $interaction
        ]);
    }
}
