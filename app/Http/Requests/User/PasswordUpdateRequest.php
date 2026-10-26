<?php

namespace App\Http\Requests\User;

use App\Rules\Auth\CurrentPassword;
use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                app(CurrentPassword::class),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
            ],
            'password_confirmation' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
