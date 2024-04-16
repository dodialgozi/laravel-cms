<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $categoryId = ($this->model->category_id ?? 0);
        return [
            "{$this->input}.category_name_id" => "required|string|max:100|unique:category,category_name_id,{$categoryId},category_id",
            "{$this->file}.category_thumbnail" => 'nullable|mimes:jpg,png',
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->file}.category_thumbnail.mimes" => ':attribute harus berupa file :values.',
            "{$this->input}.category_name.required" => ":attribute harus diinputkan.",
            "{$this->input}.category_name.unique" => ":attribute sudah terdaftar.",
            "{$this->input}.category_name.string" => ":attribute harus berupa string.",
            "{$this->input}.category_name.max" => ":attribute maksimal :max karakter.",
        ];
    }

    public function attributes(): array
    {
        return [
            "{$this->input}.category_name_id" => 'Nama Kategori',
            "{$this->file}.category_thumbnail" => 'Thumbnail',
        ];
    }
}
