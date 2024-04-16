<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends BaseRequest
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
            "{$this->input}.location_title" => "required|string|max:100",
            "{$this->input}.location_status" => "required",
            "{$this->input}.meta_title" => "required",
            "{$this->input}.meta_keyword" => "required",
            "{$this->input}.meta_description" => "nullable|string|max:300",
            "{$this->file}.first_image" => 'nullable|mimes:jpg,png',
            "{$this->file}.thumbnail" => 'nullable|mimes:jpg,png',
            "{$this->file}.medium_thumbnail" => 'nullable|mimes:jpg,png',
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->file}.*.mimes" => ':attribute harus berupa file :values.',
            "{$this->input}.*.required" => ":attribute harus diinputkan.", 
            "{$this->input}.*.unique" => ":atribute sudah terdaftar.",
            "{$this->input}.*.string" => ":attribute harus berupa string.",
            "{$this->input}.*.max" => ":attribute maksimal :max karakter.",
        ];
    }

    public function attributes() : array
    {
        return [
            "{$this->input}.location_title" => 'Judul',
            "{$this->input}.location_status" => 'Status',
            "{$this->input}.meta_title" => "Meta Title",
            "{$this->input}.meta_keyword" => "Meta Keyword",
            "{$this->input}.meta_description" => "Meta Description",
            "{$this->file}.first_image" => 'Gambar Utama',
            "{$this->file}.thumbnail" => 'Thumbnail',
            "{$this->file}.medium_thumbnail" => 'Medium Thumbnail',
        ];
    }
}
