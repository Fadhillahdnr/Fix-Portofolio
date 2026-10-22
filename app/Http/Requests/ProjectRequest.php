<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $id = $this->route('project')?->id;

        return ['title' => ['required', 'string', 'max:150'], 'slug' => ['required', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($id)], 'summary' => ['required', 'string', 'max:600'], 'problem' => ['nullable', 'string', 'max:10000'], 'solution' => ['nullable', 'string', 'max:10000'], 'result' => ['nullable', 'string', 'max:10000'], 'role' => ['nullable', 'string', 'max:120'], 'demo_url' => ['nullable', 'url', 'max:2048'], 'repository_url' => ['nullable', 'url', 'max:2048'], 'status' => ['required', Rule::in(['draft', 'published'])], 'published_at' => ['nullable', 'date'], 'is_featured' => ['nullable', 'boolean'], 'sort_order' => ['nullable', 'integer', 'min:0'], 'cover' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/avif', 'max:8192'], 'categories' => ['array'], 'categories.*' => ['integer', 'exists:categories,id'], 'skills' => ['array'], 'skills.*' => ['integer', 'exists:skills,id']];
    }
}
