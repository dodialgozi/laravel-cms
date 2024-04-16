<?php

namespace App\Http\Requests;

class SliderRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "{$this->input}.slider_title" => 'required|string|max:100',
            "{$this->input}.slider_description" => 'required|string',
            "{$this->file}.slider_image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "{$this->input}.slider_title.required" => ':attribute harus diinputkan.',
            "{$this->input}.slider_title.string" => ':attribute harus berupa string.',
            "{$this->input}.slider_title.max" => ':attribute maksimal :max karakter.',

            "{$this->input}.slider_description.required" => ':attribute harus diinputkan.',
            "{$this->input}.slider_description.string" => ':attribute harus berupa string.',

            "{$this->file}.slider_image.image" => ':attribute harus berupa gambar.',
            "{$this->file}.slider_image.mimes" => ':attribute harus berupa file :values.',
            "{$this->file}.slider_image.max" => ':attribute maksimal :max KB.',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            "{$this->input}.slider_title" => 'Judul',
            "{$this->input}.slider_description" => 'Deskripsi',
            "{$this->file}.slider_image" => 'Gambar',
        ];
    }
}
