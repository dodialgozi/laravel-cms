<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// THIS IS ONLY EXAMPLE 

class RoleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "{$this->input}.name" => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->input}.name.required" => 'A title is required',
        ];
    }

    public function attributes() : array
    {
        return [
            "{$this->input}.name" => 'Nama',
        ];
    }
}
