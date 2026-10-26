<?php

namespace App\Http\Requests\Branches;

use App\Enums\Delete;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'approval' => [
                'required',
                Rule::in(Delete::values()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'approval.required' => 'Tekan hapus atau batal!',
            'approval.in' => 'Tekan hapus atau batal',
        ];
    }
}
