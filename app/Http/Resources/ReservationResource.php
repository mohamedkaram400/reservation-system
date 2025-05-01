<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $localTime = Carbon::parse($this->reservation_date)
        ->setTimezone(Auth::user()->timezone)
        ->format('d-m-Y h:i A');

        return [
            "id"                => $this->id,
            'user_id'           => $this->user_id,
            'service_id'        => $this->service_id,
            'reservation_date'  => $localTime,
            'status'            => $this->status, 
        ];
    }
}
