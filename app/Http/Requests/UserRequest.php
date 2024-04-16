<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $userId = ($this->model->user_id ?? 0);
        return [
            "{$this->file}.user_photo" => 'nullable|mimes:jpg,png',
            "{$this->input}.user_email" => "required|unique:user,user_email,{$userId},user_id|email:rfc,dns",
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->file}.user_photo.mimes" => ':attribute harus berupa file :values.',
            "{$this->input}.user_email.required" => "Email harus diinputkan", 
            "{$this->input}.user_email.unique" => "Email sudah terdaftar", 
            "{$this->input}.user_email.email" => "Harap inputkan email dengan benar", 
        ];
    }

    public function attributes() : array
    {
        return [
            "{$this->file}.user_photo" => 'Foto Pengguna',
        ];
    }
}
