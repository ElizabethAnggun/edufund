<?php

namespace App\Http\Requests;

use App\Enums\FundingCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FundingRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'funding_category' => ['required', new Enum(FundingCategory::class)],
        ];
    }
}
