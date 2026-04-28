<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('accessAdmin') ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $photoRule = $this->isMethod('post') ? 'required' : 'nullable';

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:255'],
            'current_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'heading' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'photo_path' => [$photoRule, 'image', 'mimes:jpg,jpeg,png,webp', 'max:8048'],
            'resume_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:draft,published,archived'],
            'experiences' => ['nullable', 'array', 'max:5'],
            'experiences.*.title' => ['nullable', 'string', 'max:255'],
            'experiences.*.company' => ['nullable', 'string', 'max:255'],
            'experiences.*.company_url' => ['nullable', 'url', 'max:255'],
            'experiences.*.location' => ['nullable', 'string', 'max:255'],
            'experiences.*.duration' => ['nullable', 'string', 'max:100'],
            'experiences.*.description' => ['nullable', 'string', 'max:1000'],
            'educations' => ['nullable', 'array', 'max:4'],
            'educations.*.degree' => ['nullable', 'string', 'max:255'],
            'educations.*.institution' => ['nullable', 'string', 'max:255'],
            'educations.*.year' => ['nullable', 'string', 'max:10'],
            'educations.*.description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
