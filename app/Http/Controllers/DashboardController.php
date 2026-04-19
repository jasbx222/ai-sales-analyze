<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientAnalysis;
use App\Models\Interaction;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $user = $request->user();

        // Total analyses performed by this user (or all if user is an admin, assuming role based)
        // From Stitch layout: Total Analysis and Clients count
        $totalAnalyses = ClientAnalysis::whereHas('interaction', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        // Unique clients user has interacted with, or total clients (if clients are global, but let's assume they are shared or user-specific)
        $totalClients = Client::count(); // Keeping it global for now, or filter by user interactions

        // Recent activity: get the latest 5 interactions with analysis
        $recentInteractions = Interaction::with(['client', 'analysis'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'data' => [
                'total_analyses' => $totalAnalyses,
                'total_clients' => $totalClients,
                'monthly_target_percentage' => 85, // Mock value as per Stitch design
                'recent_activity' => $recentInteractions->map(function($interaction) {
                    $hasAnalysis = !is_null($interaction->analysis);
                    return [
                        'id' => $interaction->id,
                        'client_name' => $interaction->client->name,
                        'type' => $hasAnalysis ? 'analysis_completed' : 'interaction_added',
                        'title' => $hasAnalysis 
                            ? "تم تحليل ملاحظة \"{$interaction->client->name}\"" 
                            : "تحديث بيانات \"{$interaction->client->name}\"",
                        'time_ago' => $interaction->created_at->diffForHumans(),
                        'by' => $hasAnalysis ? 'بواسطة النظام' : $interaction->user->name
                    ];
                })
            ]
        ]);
    }
}
