<?php

namespace App\Http\Controllers;

use App\Ai\Agents\SalesClientAnalysisAgent;
use App\Models\AiRun;
use App\Models\Client;
use App\Models\ClientAnalysis;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Files;

class SalesController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate([
            'client_phone' => 'required|string',
            'client_name' => 'nullable|string',
            'product_type' => 'required|string',
            'note' => 'nullable|string',
            'interaction_context' => 'nullable|string',
            'interaction_stage' => 'nullable|string',
            'client_history' => 'nullable|string',
            'previous_notes' => 'nullable|string',
            'product_context' => 'nullable|string',
            'image' => 'required_without:note|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $client = Client::firstOrCreate(
            ['phone' => $request->client_phone],
            ['name' => $request->client_name]
        );

        $interaction = Interaction::create([
            'client_id' => $client->id,
            'user_id' => $request->user()->id,
            'product_type' => $request->product_type,
            'interaction_context' => $request->interaction_context,
            'interaction_stage' => $request->interaction_stage,
            'note' => $request->note,
        ]);

        $storedImage = null;

    

        $prompt = "حلل بيانات العميل والمحادثة التالية بدقة.\n";
        $prompt .= "تفاصيل العميل:\n";
        $prompt .= "- نوع المنتج: {$request->product_type}\n";

        if ($request->filled('note')) {
            $prompt .= "- ملاحظة المندوب: {$request->note}\n";
        }

        if ($request->filled('interaction_context')) {
            $prompt .= "- سياق التواصل: {$request->interaction_context}\n";
        }

        if ($request->filled('interaction_stage')) {
            $prompt .= "- مرحلة العميل: {$request->interaction_stage}\n";
        }

        if ($request->filled('client_history')) {
            $prompt .= "- تاريخ التعاملات: {$request->client_history}\n";
        }

        if ($request->filled('previous_notes')) {
            $prompt .= "- ملاحظات سابقة: {$request->previous_notes}\n";
        }

        if ($request->filled('product_context')) {
            $prompt .= "- سياق المنتج: {$request->product_context}\n";
        }

        if ($storedImage) {
            $prompt .= "\nصورة المحادثة المرفقة:\n";
            $prompt .= "- {$storedImage['url']}\n";
            $prompt .= "اعتمد على الصورة المرفقة لتحليل النص والمحادثة الظاهرة داخلها إن أمكن.\n";
        }
   
$attachments = [];

if ($request->hasFile('image')) {
    $path = $request->file('image')->store('sales/interactions', 'public');

    $attachments[] = Files\Image::fromStorage($path, disk: 'public');
}

$agentResult = (new SalesClientAnalysisAgent)->prompt(
    $prompt,
    attachments: $attachments
);
        $aiRun = AiRun::create([
            'interaction_id' => $interaction->id,
            'provider' => 'gemini',
            'model' => 'default',
            'status' => 'pending',
            'started_at' => now(),
        ]);

        try {
            $agentResult = (new SalesClientAnalysisAgent)->prompt($prompt);

            ClientAnalysis::create([
                'interaction_id' => $interaction->id,
                'client_profile' => $agentResult['client_profile'] ?? null,
                'client_type' => $agentResult['client_type'] ?? null,
                'interest_level' => $agentResult['interest_level'] ?? null,
                'price_sensitivity' => $agentResult['price_sensitivity'] ?? null,
                'trust_level' => $agentResult['trust_level'] ?? null,
                'buying_probability' => isset($agentResult['buying_probability']) ? (int) $agentResult['buying_probability'] : null,
                'main_objection' => $agentResult['main_objection'] ?? null,
                'psychological_trigger' => $agentResult['psychological_trigger'] ?? null,
                'recommended_strategy' => $agentResult['recommended_strategy'] ?? null,
                'next_best_action' => $agentResult['next_best_action'] ?? null,
                'suggested_message' => $agentResult['suggested_message'] ?? null,
                'follow_up_urgency' => $agentResult['follow_up_urgency'] ?? null,
                'analysis_confidence' => isset($agentResult['analysis_confidence']) ? (int) $agentResult['analysis_confidence'] : null,
                'notes' => $agentResult['notes'] ?? null,
            ]);

            $aiRun->update([
                'status' => 'success',
                'finished_at' => now(),
            ]);

            $interaction->load('analysis');

            return response()->json([
                'message' => 'تم التحليل بنجاح',
                'data' => $interaction,
                'image' => $storedImage,
            ]);
        } catch (\Throwable $e) {
            $aiRun->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'finished_at' => now(),
            ]);

            return response()->json([
                'error' => 'تعذر تحليل البيانات من الذكاء الاصطناعي.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}