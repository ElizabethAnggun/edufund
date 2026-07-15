<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:20',
            'address' => 'required|string',
            'headmaster_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'accreditation_number' => 'required|string|max:50',
            'accreditation_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'stellar_wallet_address' => 'nullable|string|max:255',
        ];
    }
}
