<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            // 'last_emailed_at' => $this->last_emailed_at ? $this->last_emailed_at->toISOString() : null,
            'created_at' => $this->created_at->toISOString(),
            'interactions_count' => $this->whenCounted('interactions', $this->interactions_count, $this->interactions()->count()),
            'suggested_follow_up' => $this->whenLoaded('latestAnalysis', function () {
                return $this->latestAnalysis?->buying_probability > 80;
            }, function () {
                // Return false if not loaded to avoid N+1 in basic lists,
                // but for single resource we might want more detail
                return $this->latestAnalysis?->buying_probability > 80;
            }),
        ];
    }
}
