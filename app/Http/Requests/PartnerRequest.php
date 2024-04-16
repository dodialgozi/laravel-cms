<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $partnerId = ($this->model->partner_id ?? 0);
        return [
            "{$this->input}.partner_name" => "required|string|max:100",
            "{$this->file}.partner_logo" => 'nullable|mimes:jpg,png|max:2048',
            "{$this->input}.partner_url" => "nullable|url|unique:partner,partner_url,{$partnerId},partner_id",
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->input}.*.required" => ":attribute harus diinputkan.",
            "{$this->input}.*.unique" => ":atribute sudah terdaftar.",
            "{$this->input}.*.string" => ":attribute harus berupa string.",
            "{$this->input}.*.max" => ":attribute maksimal :max karakter.",
            "{$this->input}.*.url" => ":attribute harus berupa url.",
            "{$this->file}.*.mimes" => ':attribute harus berupa file :values.',
            "{$this->file}.*.max" => ':attribute maksimal :max KB.',
        ];
    }

    public function attributes(): array
    {
        return [
            "{$this->input}.partner_name" => 'Nama',
            "{$this->file}.partner_logo" => 'Logo',
            "{$this->input}.partner_url" => 'URL',
        ];
    }
}
