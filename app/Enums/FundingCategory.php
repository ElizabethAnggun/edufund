<?php

namespace App\Enums;

enum FundingCategory: string
{
    case TUITION = 'tuition';
    case BOOKS = 'books';
    case LIVING_EXPENSES = 'living_expenses';
    case RESEARCH = 'research';
    case OTHER = 'other';
}
