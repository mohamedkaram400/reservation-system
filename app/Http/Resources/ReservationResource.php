<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                => $this->id,
            'user_id'           => $this->user_id,
            'service_id'        => $this->service_id,
            'reservation_date'  => Carbon::make($this->reservation_date)->format('Y-m-d H:i:s A'),
            'status'            => $this->status, 
        ];
    }
}
