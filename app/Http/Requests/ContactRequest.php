<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['email' => strtolower(trim((string) $this->email))]);
    }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:100'], 'email' => ['required', 'email:rfc', 'max:180'], 'subject' => ['required', 'string', 'max:180'], 'message' => ['required', 'string', 'min:20', 'max:5000'], 'website' => ['nullable', 'max:0']];
    }
}
