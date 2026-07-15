<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VerifySchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:verify,reject',
            'reason' => 'required_if:action,reject|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required_if' => 'Please provide a reason when rejecting a school.',
        ];
    }
}
