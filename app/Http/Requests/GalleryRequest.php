<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $galleryId = ($this->model->gallery_id ?? 0);
        return [
            "{$this->input}.gallery_title_id" => 'required|string|max:100',
            "{$this->input}.gallery_description_id" => 'nullable|string',
            "{$this->file}.gallery_image" => 'nullable|mimes:jpg,png',
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->input}.gallery_title_id.required" => ':attribute  Judul wajib diisi',
            "{$this->input}.gallery_title_id.string" => ':attribute  Judul harus berupa teks',
            "{$this->input}.gallery_title_id.max" => ':attribute  Judul maksimal 100 karakter',

            "{$this->input}.gallery_description_id.string" => ':attribute  Deskripsi harus berupa teks',

            "{$this->file}.gallery_image.mimes" => ':attribute  Gambar harus berupa file :values',
        ];
    }

    public function attributes(): array
    {
        return [
            "{$this->input}.gallery_title_id" => 'Judul',
            "{$this->input}.gallery_description_id" => 'Deskripsi',
            "{$this->file}.gallery_image" => 'Gambar',
        ];
    }
}
