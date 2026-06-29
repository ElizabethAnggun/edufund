<?php

namespace App\Http\Requests;

use App\Enums\EducationLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StudentProfileRequest extends FormRequest
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
            'profile_photo' => 'nullable|image|max:5120',
            'nisn' => 'nullable|string|max:20|unique:student_profiles,nisn,' . optional(auth()->user()->studentProfile)->id,
            'nim' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'education_level' => ['required', new Enum(EducationLevel::class)],
            'major' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'date_of_birth' => 'required|date',
            'gpa' => 'nullable|numeric|between:0,4',
            'semester' => 'required|integer|min:1',
        ];
    }
}
