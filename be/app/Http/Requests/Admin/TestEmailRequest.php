<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('email-setting.test') ?? false;
    }

    public function rules(): array
    {
        return [
            'recipient' => ['required', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format :attribute tidak valid.',
            'max' => ':attribute maksimal :max karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'recipient' => 'Alamat tujuan',
        ];
    }
}
