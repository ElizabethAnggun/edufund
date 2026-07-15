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
        $rules = [
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'education_level' => ['required', new Enum(EducationLevel::class)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string',
            'parent_income' => 'nullable|numeric|min:0',
        ];

        $educationLevel = $this->input('education_level');

        if (in_array($educationLevel, [EducationLevel::SMA->value, EducationLevel::SMK->value])) {
            // High School Rules
            $rules = array_merge($rules, [
                'school_name' => 'required|string|max:255',
                'school_npsn' => 'nullable|string|max:20',
                'school_address' => 'nullable|string',
                'major' => 'required|string|max:100',
                'class' => 'required|string|max:20',
                'nisn' => 'required|string|max:20|unique:student_profiles,nisn,' . optional(auth()->user()->studentProfile)->id,
                'graduation_year' => 'nullable|integer|min:2000|max:' . date('Y'),
                'parent_name' => 'nullable|string|max:255',
                'student_status' => 'nullable|string|max:50',
                'academic_rank' => 'required|string|max:100',
            ]);
        } else {
            // University Rules (D1, D2, D3, D4, S1, S2, S3)
            $rules = array_merge($rules, [
                'university_name' => 'required|string|max:255',
                'university_address' => 'nullable|string',
                'faculty' => 'required|string|max:100',
                'study_program' => 'required|string|max:100',
                'nim' => 'required|string|max:20|unique:student_profiles,nim,' . optional(auth()->user()->studentProfile)->id,
                'semester' => 'required|integer|min:1',
                'gpa' => 'required|numeric|between:0,4',
                'expected_graduation' => 'nullable|date|after:today',
                'scholarship_status' => 'nullable|string|max:50',
                'parent_name' => 'nullable|string|max:255',
            ]);
        }

        return $rules;
    }
}
