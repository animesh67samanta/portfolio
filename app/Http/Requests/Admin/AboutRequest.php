<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $photoRule = $this->isMethod('post') ? 'required' : 'nullable';

        return [
            'heading' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'photo_path' => [$photoRule, 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'resume_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:draft,published,archived'],
        ];
    }
}
