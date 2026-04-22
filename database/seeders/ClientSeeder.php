<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientAnalysis;
use App\Models\Interaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 'admin')->first();
        if (! $user) {
            return;
        }

        $clients = [
            [
                'name' => 'أحمد السعدي',
                'phone' => '0551234567',
                'email' => 'ahmed@example.com',
                'probability' => 85,
            ],
            [
                'name' => 'شركة الحلول المتقدمة',
                'phone' => '0557654321',
                'email' => 'info@solutions.com',
                'probability' => 40,
            ],
            [
                'name' => 'سارة المنصور',
                'phone' => '0550001112',
                'email' => 'sara@example.com',
                'probability' => 95,
            ],
            [
                'name' => 'فيصل الخالدي',
                'phone' => '0559998887',
                'email' => 'faisal@example.com',
                'probability' => 65,
            ],
        ];

        foreach ($clients as $clientData) {
            $client = Client::create([
                'name' => $clientData['name'],
                'phone' => $clientData['phone'],
                'email' => $clientData['email'],
                'last_emailed_at' => null,
            ]);

            $interaction = Interaction::create([
                'client_id' => $client->id,
                'user_id' => $user->id,
                'product_type' => 'خدمات برمجية',
                'interaction_context' => 'اجتماع تعريفي بالخدمات',
                'interaction_stage' => 'Negotiation',
                'note' => 'العميل مهتم جداً ويرغب في البدء فوراً',
            ]);

            ClientAnalysis::create([
                'interaction_id' => $interaction->id,
                'client_profile' => 'شركة تقنية',
                'client_type' => 'Potential',
                'interest_level' => 'High',
                'buying_probability' => $clientData['probability'],
                'suggested_message' => "مرحباً يا {$client->name}، نود متابعة ما بدأنا به...",
                'next_best_action' => 'إرسال عرض فني',
            ]);
        }
    }
}
