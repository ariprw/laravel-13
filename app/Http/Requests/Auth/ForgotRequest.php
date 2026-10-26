<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ForgotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email),
        ]);
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9@._-]+$/',
                Rule::exists('users', 'email')->where(function ($query) {
                    $query->whereNotNull('email_verified_at')
                    ->where('status', 'aktif');
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'Email tidak valid atau tidak ditemukan',
        ];
    }
}
