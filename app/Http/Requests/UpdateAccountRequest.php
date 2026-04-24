<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname'  => ['required', 'string', 'max:255'],
            'email'     => ['nullable', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'bio'       => ['nullable', 'string', 'max:1000'],
            'logo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
        ];
    }
}
