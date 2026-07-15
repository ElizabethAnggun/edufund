<?php

namespace App\Enums;

enum DisbursementStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
