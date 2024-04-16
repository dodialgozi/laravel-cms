<?php

namespace App\Http\Requests;

class InstanceRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $instanceId = ($this->model->instance_id ?? 0);
        return [
            "{$this->input}.instance_name" => 'required|string|max:100',
            "{$this->file}.instance_thumbnail" => 'nullable|mimes:jpg,png',
            "{$this->input}.instance_domain" => "required|string|max:100|unique:instance,instance_domain,{$instanceId},instance_id",
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->input}.instance_name.required" => ':attribute  Instansi wajib diisi',
            "{$this->input}.instance_name.string" => ':attribute  Instansi harus berupa teks',
            "{$this->input}.instance_name.max" => ':attribute  Instansi maksimal 100 karakter',
            "{$this->input}.instance_name.unique" => ':attribute  Instansi sudah terdaftar',

            "{$this->file}.instance_thumbnail.mimes" => ':attribute  Instansi harus berupa file :values',

            "{$this->input}.instance_domain.required" => ':attribute Instansi wajib diisi',
            "{$this->input}.instance_domain.string" => ':attribute Instansi harus berupa teks',
            "{$this->input}.instance_domain.max" => ':attribute Instansi maksimal 100 karakter',
            "{$this->input}.instance_domain.unique" => ':attribute Instansi sudah terdaftar',
        ];
    }

    public function attributes(): array
    {
        return [
            "{$this->input}.instance_name" => 'Nama Instansi',
            "{$this->file}.instance_logo" => 'Logo Instansi',
            "{$this->input}.instance_domain" => 'Domain Instansi',
        ];
    }
}
