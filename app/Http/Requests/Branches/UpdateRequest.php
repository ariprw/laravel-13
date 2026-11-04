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
            'phone' => formatNumber($this->phone),
            'email' => strtolower($this->email),
            'address' => ucwords($this->address),
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
            'phone' => [
                'nullable',
                'string',
                'max:255',
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
            ],
            'address' => [
                'nullable',
                'string',
                'max:800',
            ],
            'maps' => [
                'nullable',
                'string',
                'max:800',
            ],
            'status' => [
                'required',
                Rule::in(Status::values()),
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:10240',
            ],
            'removeLogo' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
