<?php

namespace App\Http\Requests\Auth;

use App\Enums\User\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => ucwords($this->name),
            'gender' => strtolower($this->gender),
            'email' => strtolower($this->email),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'gender' => [
                'required',
                Rule::in(Gender::values()),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
            ],
        ];
    }
}
