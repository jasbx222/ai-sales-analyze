<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'last_emailed_at'];

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    public function latestAnalysis()
    {
        return $this->hasOneThrough(
            ClientAnalysis::class,
            Interaction::class,
            'client_id',
            'interaction_id',
            'id',
            'id'
        )->latest('client_analyses.id');
    }
}
