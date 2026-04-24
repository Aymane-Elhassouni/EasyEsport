<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bio'            => ['nullable', 'string', 'max:1000'],
            'total_matches'  => ['nullable', 'integer', 'min:0'],
            'total_trophies' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
