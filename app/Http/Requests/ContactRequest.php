<?php

namespace App\Http\Requests;

class ContactRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "{$this->input}.key" => 'required|string|max:100',
            "{$this->input}.value" => 'required|string',
            "{$this->file}.icon" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->input}.key.required" => ':attribute harus diinputkan.',
            "{$this->input}.key.string" => ':attribute harus berupa string.',
            "{$this->input}.key.max" => ':attribute maksimal :max karakter.',

            "{$this->input}.value.required" => ':attribute harus diinputkan.',
            "{$this->input}.value.string" => ':attribute harus berupa string.',

            "{$this->file}.icon.image" => ':attribute harus berupa gambar.',
            "{$this->file}.icon.mimes" => ':attribute harus berupa file :values.',
            "{$this->file}.icon.max" => ':attribute maksimal :max KB.',
        ];
    }

    public function attributes(): array
    {
        return [
            "{$this->input}.key" => 'Key',
            "{$this->input}.value" => 'Value',
            "{$this->file}.icon" => 'Icon',
        ];
    }
}
