<?php

namespace App\Console\Commands;

use App\Ai\Agents\FollowUpDecisionAgent;
use App\Mail\DynamicFollowUpMail;
use App\Models\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAiFollowUps extends Command
{
    protected $signature = 'app:send-ai-follow-ups';

    protected $description = 'تحليل تقدم العملاء وإرسال متابعات بالبريد الإلكتروني باستخدام الذكاء الاصطناعي';

    public function handle(FollowUpDecisionAgent $agent)
    {
        $this->info('بدء عملية المتابعة ذ...');

        // نجلب العملاء الذين لديهم إيميل ولم نرسل لهم في آخر 3 أيام، ولديهم تفاعلات حديثة
        $clients = Client::whereNotNull('email')
            ->where(function ($query) {
                $query->whereNull('last_emailed_at')
                    ->orWhere('last_emailed_at', '<=', now()->subDays(3));
            })
            ->with(['interactions' => function ($query) {
                $query->latest()->limit(5); // آخر 5 تفاعلات للتحليل
            }])
            ->get();

        if ($clients->isEmpty()) {
            $this->info('لا يوجد عملاء مؤهلون للمتابعة حالياً.');

            return;
        }

        foreach ($clients as $client) {
            $this->info("جاري تحليل العميل: {$client->name}");

            $interactionsText = $client->interactions->map(function ($i) {
                return "[{$i->created_at->toDateString()}] {$i->product_type}: {$i->note}";
            })->implode("\n");

            if (empty($interactionsText)) {
                $this->warn("العميل {$client->name} ليس لديه تفاعلات للتحليل. تخطي...");

                continue;
            }

            try {
                $prompt = "اسم العميل: {$client->name}\nالتفاعلات الأخيرة:\n{$interactionsText}\n\nحلل البيانات وقرر ما إذا كان يجب المتابعة الآن.";
                $decision = $agent->prompt($prompt);

                if ($decision['should_send_now']) {
                    $this->info('الذكاء الاصطناعي يقترح الإرسال: '.$decision['reasoning']);

                    Mail::to($client->email)->send(new DynamicFollowUpMail(
                        subjectText: $decision['subject_text'],
                        title: $decision['email_title'],
                        messageText: $decision['email_message'],
                        buttonText: $decision['button_text'],
                        buttonUrl: $decision['button_url'],
                    ));

                    $client->update(['last_emailed_at' => now()]);
                    $this->info("تم إرسال الإيميل بنجاح لـ {$client->email}");
                } else {
                    $this->info('الذكاء الاصطناعي يقترح عدم الإرسال حالياً: '.$decision['reasoning']);
                }
            } catch (\Exception $e) {
                $this->error("خطأ أثناء معالجة العميل {$client->name}: ".$e->getMessage());
            }
        }

        $this->info('اكتملت عملية المتابعة الذكية.');
    }
}
