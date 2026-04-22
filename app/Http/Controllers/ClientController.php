<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query()->with(['latestAnalysis']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter: follow-up
        if ($request->input('filter') === 'follow-up') {
            $query->whereHas('interactions', function ($q) {
                $q->where('id', function ($sub) {
                    $sub->selectRaw('max(id)')
                        ->from('interactions')
                        ->whereColumn('client_id', 'clients.id');
                })->whereHas('analysis', function ($sub) {
                    $sub->where('buying_probability', '>', 80);
                });
            })->where(function ($q) {
                $q->whereNull('last_emailed_at')
                    ->orWhere('last_emailed_at', '<', now()->subDays(7));
            });
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return ClientResource::collection($clients);
        }

        return Inertia::render('clients/index', [
            'clients' => ClientResource::collection($clients),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:clients,phone',
            'email' => 'nullable|email|max:255|unique:clients,email',
        ]);

        $client = Client::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Client created successfully',
                'data' => new ClientResource($client),
            ], 201);
        }

        return back()->with('success', 'تم إضافة العميل بنجاح');
    }

    public function show(Client $client, Request $request)
    {
        // Load interactions and their analysis
        $client->load(['interactions' => function ($query) {
            $query->latest()->with('analysis');
        }, 'latestAnalysis']);

        if ($request->wantsJson()) {
            return new ClientResource($client);
        }

        return Inertia::render('clients/show', [
            'client' => new ClientResource($client),
        ]);
    }
}
