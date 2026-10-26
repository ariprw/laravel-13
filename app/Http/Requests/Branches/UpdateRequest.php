<?php

namespace App\Http\Requests\Branches;

use App\Enums\Branches\Status;
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
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:5',
                'max:255',
                Rule::unique('branches', 'name')->ignore($this->route('branches')),
            ],
            'status' => [
                'required',
                Rule::in(Status::values()),
            ],
        ];
    }
}
