<?php

namespace App\Models\Enums;

enum RequestStatus: int
{
    case APPLIED = 1;
    case DISMISS = 2;
}
