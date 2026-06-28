<?php

namespace App\Enums;

enum FundingRequestStatus: string
{
    case DRAFT = 'draft';
    case PENDING_SCHOOL_APPROVAL = 'pending_school_approval';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
