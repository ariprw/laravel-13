<?php

namespace App\Http\Requests\User;

use App\Enums\User\Status;
use App\Enums\User\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => ucwords($this->name),
            'status' => strtolower($this->status),
            'role' => strtolower($this->role),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:4',
                'max:255',
            ],
            'status' => [
                'required',
                Rule::in(Status::values()),
            ],
            'role' => [
                'required',
                Rule::in(Role::values()),
            ],
            'branch' => [
                'nullable',
                Rule::exists('branches', 'id'),
            ],
        ];
    }
}
