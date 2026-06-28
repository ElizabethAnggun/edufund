<?php

namespace App\Enums;

enum UserRole: string
{
    case STUDENT = 'student';
    case SCHOOL = 'school';
    case DONOR = 'donor';
    case ADMIN = 'admin';
}
