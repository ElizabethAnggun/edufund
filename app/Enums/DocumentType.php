<?php

namespace App\Enums;

enum DocumentType: string
{
    case STUDENT_CARD = 'student_card';
    case TUITION_INVOICE = 'tuition_invoice';
    case TRANSCRIPT = 'transcript';
    case CERTIFICATE = 'certificate';
}
