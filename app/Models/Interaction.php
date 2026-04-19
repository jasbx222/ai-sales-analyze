<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'product_type',
        'interaction_context',
        'interaction_stage',
        'note',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function analysis()
    {
        return $this->hasOne(ClientAnalysis::class);
    }

    public function aiRuns()
    {
        return $this->hasMany(AiRun::class);
    }
}
