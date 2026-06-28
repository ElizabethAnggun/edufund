<?php

namespace App\Enums;

enum BlockchainTransactionType: string
{
    case DONATION = 'donation';
    case FUND_RELEASE = 'fund_release';
    case ACHIEVEMENT = 'achievement';
}
