<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') || $this->user()?->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            'title'  => 'required|string|max:255',
            'body'   => 'required|string',
            'banner' => 'nullable|image|max:2048',
            'status' => 'required|in:public,private',
        ];
    }
}
