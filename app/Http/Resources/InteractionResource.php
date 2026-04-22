<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InteractionResource extends JsonResource
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
            'client_id' => $this->client_id,
            'product_type' => $this->product_type,
            'note' => $this->note,
            'interaction_context' => $this->interaction_context,
            'interaction_stage' => $this->interaction_stage,
            'created_at' => $this->created_at->toISOString(),
            'client' => new ClientResource($this->whenLoaded('client')),
            'analysis' => $this->whenLoaded('analysis'),
        ];
    }
}
