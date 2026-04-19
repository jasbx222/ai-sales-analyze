<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(15);
        
        return response()->json([
            'data' => $clients->items(),
            'meta' => [
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'total' => $clients->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:clients,phone',
        ]);

        $client = Client::create($validated);

        return response()->json([
            'message' => 'Client created successfully',
            'data' => $client
        ], 201);
    }

    public function show(Client $client)
    {
        // Load interactions and their analysis
        $client->load(['interactions' => function($query) {
            $query->latest()->with('analysis');
        }]);

        return response()->json([
            'data' => $client
        ]);
    }
}
