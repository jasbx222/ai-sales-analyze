<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

#[Provider('gemini')]
class FollowUpDecisionAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return <<<'EOF'
أنت مساعد ذكي متخصص في اتخاذ قرار حول ما إذا كان يجب إرسال إيميل متابعة لعميل معين بناءً على تحليل بياناته وتفاعلاته الأخيرة. هدفك هو مساعدة فريق المبيعات في تحديد العملاء الذين يحتاجون إلى متابعة فورية لزيادة فرص إتمام الصفقة.

       
EOF;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'should_send_now' => $schema->boolean()->description('هل يجب إرسال إيميل للعميل الآن؟')->required(),
            'subject_text' => $schema->string()->description('عنوان الإيميل (Subject)')->required(),
            'email_title' => $schema->string()->description('العنوان الرئيسي داخل الإيميل')->required(),
            'email_message' => $schema->string()->description('نص الرسالة (بالعربية)')->required(),
            'button_text' => $schema->string()->description('نص الزر (مثلاً: تواصل معنا الآن)')->required(),
            'button_url' => $schema->string()->description('رابط الزر (افتراضياً يمكن أن يكون رابط وتساب الشركة)')->required(),
            'reasoning' => $schema->string()->description('تفسير موجز لماذا اخترت الإرسال أو عدم الإرسال')->required(),
        ];
    }
}
