<?php

namespace App\Enums;

enum GuestAttendance: string
{
    case Confirmed = 'confirmed';
    case Declined = 'declined';
    case Pending = 'pending';
    case Attending = 'attending';
    case NotAttending = 'not_attending';
    case Tentative = 'tentative';
}
