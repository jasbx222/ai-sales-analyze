<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientAnalysis;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Stats
        $totalAnalyses = ClientAnalysis::whereHas('interaction', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $totalClients = Client::count();

        // Recent activity
        $recentInteractions = Interaction::with(['client', 'analysis'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($interaction) {
                $hasAnalysis = ! is_null($interaction->analysis);

                return [
                    'id' => $interaction->id,
                    'client_name' => $interaction->client->name,
                    'type' => $hasAnalysis ? 'analysis_completed' : 'interaction_added',
                    'title' => $hasAnalysis
                        ? "تم تحليل ملاحظة \"{$interaction->client->name}\""
                        : "تحديث بيانات \"{$interaction->client->name}\"",
                    'time_ago' => $interaction->created_at->diffForHumans(),
                    'by' => $hasAnalysis ? 'بواسطة النظام' : $interaction->user->name,
                ];
            });

        $pendingFollowUps = Client::whereHas('interactions', function ($q) {
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
        })->count();

        return Inertia::render('dashboard', [
            'stats' => [
                'total_analyses' => $totalAnalyses,
                'total_clients' => $totalClients,
                'pending_follow_ups' => $pendingFollowUps,
                'monthly_target_percentage' => 85, // Mapped from design
            ],
            'recent_activity' => $recentInteractions,
        ]);
    }

    public function stats(Request $request)
    {
        // This can still be used for polling/updates if needed
        return response()->json([
            'data' => $this->index($request)->props['stats'],
        ]);
    }
}
