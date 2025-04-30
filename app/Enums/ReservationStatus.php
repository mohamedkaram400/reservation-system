<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Pending   = 'pending';
    case Confirmed = 'confirmed';
    case Canceled  = 'canceled';
    case Completed = 'completed';
}