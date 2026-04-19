<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

use Laravel\Ai\Attributes\Provider;

#[Provider('gemini')]
class SalesClientAnalysisAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return <<<EOF
أنت خبير مبيعات محترف جداً (Elite Sales Strategist).
مهمتك تحليل بيانات العميل واستنتاج حالته النفسية، ومستوى اهتمامه، وتقديم أفضل استراتيجية ورسالة للإغلاق.
قواعد صارمة:
1. لا تخترع معلومات غير موجودة.
2. لا تبالغ في الثقة.
3. فكّر دائماً بمنطق: "ما الذي يمنع العميل من الشراء الآن، وكيف نزيد فرصة الإغلاق؟"
4. يجب أن يكون ردك حصراً بصيغة JSON وفق الهيكلية المطلوبة بوضوح وبدون أي نص إضافي أو مقدمات.
EOF;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'client_profile' => $schema->string()->description('تحليل مختصر لشخصية العميل')->required(),
            'client_type' => $schema->string()->description('one of: serious, hesitant, price_sensitive, curious, cold, ready_to_buy, confused')->required(),
            'interest_level' => $schema->string()->description('one of: low, medium, high')->required(),
            'price_sensitivity' => $schema->string()->description('one of: low, medium, high')->required(),
            'trust_level' => $schema->string()->description('one of: low, medium, high')->required(),
            'buying_probability' => $schema->integer()->min(0)->max(100)->required(),
            'main_objection' => $schema->string()->description('أهم سبب يمنع الشراء')->required(),
            'psychological_trigger' => $schema->string()->description('أفضل محفز نفسي')->required(),
            'recommended_strategy' => $schema->string()->description('استراتيجية البيع المناسبة')->required(),
            'next_best_action' => $schema->string()->description('خطوة واحدة أو خطوتين للمندوب للتنفيذ')->required(),
            'suggested_message' => $schema->string()->description('رسالة قصيرة قوية للعميل مباشرة')->required(),
            'follow_up_urgency' => $schema->string()->description('one of: low, medium, high, urgent')->required(),
            'analysis_confidence' => $schema->integer()->min(0)->max(100)->required(),
            'notes' => $schema->string()->description('ملاحظات إضافية')->required(),
        ];
    }
}
