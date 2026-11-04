<?php

namespace App\Http\Requests\Export;

use App\Enums\Export\Category;
use App\Rules\Export\HasRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'module' => [
                'required',
                'string',
                app(HasRegistry::class),
            ],
            'category' => [
                'required',
                Rule::in(Category::values())
            ],
            'startDate' => [
                'nullable',
                'date_format:Y-m-d'
            ],
            'endDate' => [
                'nullable',
                'date_format:Y-m-d'
            ],
        ];
    }
}
