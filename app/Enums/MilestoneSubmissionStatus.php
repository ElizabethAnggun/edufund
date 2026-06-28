<?php

namespace App\Enums;

enum MilestoneSubmissionStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';
}
