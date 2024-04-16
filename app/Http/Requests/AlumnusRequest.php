<?php

namespace App\Http\Requests;


class AlumnusRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $alumnusId = ($this->model->alumnus_id ?? 0);
        return [
            "{$this->input}.alumnus_nim" => "nullable|string|max:100|unique:alumnus,alumnus_nim,{$alumnusId},alumnus_id",
            "{$this->input}.alumnus_nirm" => "nullable|string|max:100|unique:alumnus,alumnus_nirm,{$alumnusId},alumnus_id",
            "{$this->input}.alumnus_nirl" => "nullable|string|max:100|unique:alumnus,alumnus_nirl,{$alumnusId},alumnus_id",
            "{$this->input}.alumnus_email" => "nullable|email|max:100|unique:alumnus,alumnus_email,{$alumnusId},alumnus_id",
            "{$this->input}.alumnus_name" => "nullable|string|max:100",
            "{$this->input}.alumnus_profession" => "nullable|string|max:100",
            "{$this->input}.alumnus_statement" => "nullable|string|max:300",
            "{$this->file}.alumnus_image" => 'nullable|mimes:jpg,png',
            "{$this->input}.alumnus_status" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->input}.*.required" => ":attribute harus diinputkan.",
            "{$this->input}.*.unique" => ":atribute sudah terdaftar.",
            "{$this->input}.*.string" => ":attribute harus berupa string.",
            "{$this->input}.*.max" => ":attribute maksimal :max karakter.",
            "{$this->file}.*.mimes" => ':attribute harus berupa file :values.',
        ];
    }

    public function attributes(): array
    {
        return [
            "{$this->input}.alumnus_nim" => 'NIM',
            "{$this->input}.alumnus_nirm" => 'NIRM',
            "{$this->input}.alumnus_nirl" => 'NIRL',
            "{$this->input}.alumnus_email" => 'Email',
            "{$this->input}.alumnus_name" => 'Nama',
            "{$this->input}.alumnus_profession" => 'Profesi',
            "{$this->input}.alumnus_statement" => 'Pernyataan',
            "{$this->file}.alumnus_image" => 'Foto',
            "{$this->input}.alumnus_status" => 'Status',
        ];
    }
}
