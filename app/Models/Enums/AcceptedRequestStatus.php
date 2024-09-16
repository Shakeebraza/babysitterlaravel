<?php

namespace App\Models\Enums;

enum AcceptedRequestStatus: int
{
    case OPEN = 0;
    case REWARDED = 1;
    case REJECTED = 2;

}
