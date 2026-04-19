<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAnalysis extends Model
{
    protected $fillable = [
        'interaction_id',
        'client_profile',
        'client_type',
        'interest_level',
        'price_sensitivity',
        'trust_level',
        'buying_probability',
        'main_objection',
        'psychological_trigger',
        'recommended_strategy',
        'next_best_action',
        'suggested_message',
        'follow_up_urgency',
        'analysis_confidence',
        'notes',
    ];

    public function interaction()
    {
        return $this->belongsTo(Interaction::class);
    }
}
