<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SalesClientAnalysisAgent;
use App\Models\AiRun;
use App\Models\Client;
use App\Models\ClientAnalysis;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Laravel\Ai\Enums\Lab;

class SalesController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate([
            'client_phone' => 'required|string',
            'client_name' => 'nullable|string',
            'product_type' => 'required|string',
            'note' => 'required|string',
            'interaction_context' => 'nullable|string',
            'interaction_stage' => 'nullable|string',
            'client_history' => 'nullable|string',
            'previous_notes' => 'nullable|string',
            'product_context' => 'nullable|string',
        ]);

        // Find or create the client
        $client = Client::firstOrCreate(
            ['phone' => $request->client_phone],
            ['name' => $request->client_name]
        );

        // Store the current interaction
        $interaction = Interaction::create([
            'client_id' => $client->id,
            'user_id' => $request->user()->id,
            'product_type' => $request->product_type,
            'interaction_context' => $request->interaction_context,
            'interaction_stage' => $request->interaction_stage,
            'note' => $request->note,
        ]);

        // Prepare the prompt
        $prompt = "تفاصيل العميل:\n";
        $prompt .= "- نوع المنتج: {$request->product_type}\n";
        $prompt .= "- ملاحظة المندوب: {$request->note}\n";
        if ($request->interaction_context) $prompt .= "- سياق التواصل: {$request->interaction_context}\n";
        if ($request->interaction_stage) $prompt .= "- مرحلة العميل: {$request->interaction_stage}\n";
        if ($request->client_history) $prompt .= "- تاريخ التعاملات: {$request->client_history}\n";
        if ($request->previous_notes) $prompt .= "- ملاحظات سابقة: {$request->previous_notes}\n";
        if ($request->product_context) $prompt .= "- سياق المنتج: {$request->product_context}\n";

        // Log the AI run initiation
        $aiRun = AiRun::create([
            'interaction_id' => $interaction->id,
            'provider' => 'gemini', // Adjust based on your default
            'model' => 'default', // Adjust as needed
            'status' => 'pending',
            'started_at' => now(),
        ]);

        try {
            // Call the AI Agent
            $agentResult = (new SalesClientAnalysisAgent)->prompt($prompt);
            
            // Note: Since this uses Structured Output, the prompt response array-access 
            // should give us our fields.
            $analysisData = [
                'interaction_id' => $interaction->id,
                'client_profile' => $agentResult['client_profile'] ?? null,
                'client_type' => $agentResult['client_type'] ?? null,
                'interest_level' => $agentResult['interest_level'] ?? null,
                'price_sensitivity' => $agentResult['price_sensitivity'] ?? null,
                'trust_level' => $agentResult['trust_level'] ?? null,
                'buying_probability' => tap(intval($agentResult['buying_probability'] ?? 0), fn($v) => filter_var($v, FILTER_VALIDATE_INT) !== false ? $v : null),
                'main_objection' => $agentResult['main_objection'] ?? null,
                'psychological_trigger' => $agentResult['psychological_trigger'] ?? null,
                'recommended_strategy' => $agentResult['recommended_strategy'] ?? null,
                'next_best_action' => $agentResult['next_best_action'] ?? null,
                'suggested_message' => $agentResult['suggested_message'] ?? null,
                'follow_up_urgency' => $agentResult['follow_up_urgency'] ?? null,
                'analysis_confidence' => tap(intval($agentResult['analysis_confidence'] ?? 0), fn($v) => filter_var($v, FILTER_VALIDATE_INT) !== false ? $v : null),
                'notes' => $agentResult['notes'] ?? null,
            ];

            // Save the analysis report
            $analysis = ClientAnalysis::create($analysisData);

            // Update AI run as success
            $aiRun->update([
                'status' => 'success',
                'finished_at' => now(),
            ]);

            // Return the full interaction with the analysis loaded
            $interaction->load('analysis');

            return response()->json(['data' => $interaction]);

        } catch (\Exception $e) {
            // Record failure
            $aiRun->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'finished_at' => now(),
            ]);

            return response()->json([
                'error' => 'تعذر تحليل البيانات من الذكاء الاصطناعي.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
