<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRun extends Model
{
    protected $fillable = [
        'interaction_id',
        'provider',
        'model',
        'status',
        'error_message',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function interaction()
    {
        return $this->belongsTo(Interaction::class);
    }
}
