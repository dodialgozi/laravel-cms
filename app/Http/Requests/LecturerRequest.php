<?php

namespace App\Http\Requests;

class LecturerRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $lecturerId = ($this->model->lecturer_id ?? 0);
        return [
            "{$this->input}.lecturer_name" => 'required|string|max:100',
            "{$this->input}.lecturer_nidn" => "required|string|max:20|unique:lecturer,lecturer_nidn,{$lecturerId},lecturer_id",
            "{$this->input}.lecturer_email" => 'required|email',
            "{$this->file}.lecturer_photo" => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            "{$this->input}.lecturer_bio_id" => 'required|string',
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
            "{$this->input}.lecturer_name.required" => ':attribute harus diinputkan.',
            "{$this->input}.lecturer_name.string" => ':attribute harus berupa string.',
            "{$this->input}.lecturer_name.max" => ':attribute maksimal :max karakter.',

            "{$this->input}.lecturer_nidn.required" => ':attribute harus diinputkan.',
            "{$this->input}.lecturer_nidn.string" => ':attribute harus berupa string.',
            "{$this->input}.lecturer_nidn.max" => ':attribute maksimal :max karakter.',
            "{$this->input}.lecturer_nidn.unique" => ':attribute sudah terdaftar.',

            "{$this->input}.lecturer_email.required" => ':attribute harus diinputkan.',
            "{$this->input}.lecturer_email.email" => ':attribute harus berupa email.',

            "{$this->file}.lecturer_photo.image" => ':attribute harus berupa gambar.',
            "{$this->file}.lecturer_photo.mimes" => ':attribute harus berupa file :values.',
            "{$this->file}.lecturer_photo.max" => ':attribute maksimal :max KB.',

            "{$this->input}.lecturer_bio_id.required" => ':attribute harus diinputkan.',
            "{$this->input}.lecturer_bio_id.string" => ':attribute harus berupa string.',
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
            "{$this->input}.lecturer_name" => 'Nama Dosen',
            "{$this->input}.lecturer_nidn" => 'NIDN',
            "{$this->input}.lecturer_email" => 'Email',
            "{$this->file}.lecturer_photo" => 'Foto',
            "{$this->input}.lecturer_bio_id" => 'Bio',
        ];
    }
}
