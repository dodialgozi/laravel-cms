<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryJurnalisRequest extends BaseRequest
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
            "{$this->input}.user_id" => "required",
            "{$this->input}.categories" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->input}.*.required" => ":attribute harus diinputkan.",
        ];
    }

    public function attributes(): array
    {
        return [
            "{$this->input}.user_id" => 'Jurnalis',
            "{$this->input}.categories" => 'Kategori'
        ];
    }
}
