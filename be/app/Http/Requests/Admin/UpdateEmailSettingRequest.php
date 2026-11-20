<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmailSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('email-setting.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'mail_driver' => ['required', Rule::in(['smtp', 'log', 'array'])],
            'mail_host' => ['required_if:mail_driver,smtp', 'nullable', 'string', 'max:255'],
            'mail_port' => ['required_if:mail_driver,smtp', 'nullable', 'integer', 'min:1', 'max:65535'],
            'mail_encryption' => ['nullable', Rule::in(['', 'tls', 'ssl'])],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_timeout' => ['nullable', 'integer', 'min:5', 'max:300'],
            'from_address' => ['required', 'email', 'max:255'],
            'from_name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'required_if' => ':attribute wajib diisi untuk SMTP.',
            'email' => 'Format :attribute tidak valid.',
            'integer' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute maksimal :max.',
            'in' => 'Pilihan :attribute tidak valid.',
            'boolean' => ':attribute tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'mail_driver' => 'Metode pengiriman',
            'mail_host' => 'Host SMTP',
            'mail_port' => 'Port SMTP',
            'mail_encryption' => 'Enkripsi',
            'mail_username' => 'Username SMTP',
            'mail_password' => 'Password SMTP',
            'mail_timeout' => 'Batas waktu',
            'from_address' => 'Alamat pengirim',
            'from_name' => 'Nama pengirim',
            'is_active' => 'Status aktif',
        ];
    }
}
